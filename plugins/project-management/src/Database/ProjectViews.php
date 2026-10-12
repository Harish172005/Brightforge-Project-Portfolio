<?php

namespace Brightforge\ProjectManager\Database;

if (!defined('ABSPATH')) {
    exit;
}

class ProjectViews
{
    private string $tableName;

    public function __construct()
    {
        global $wpdb;

        $this->tableName = $wpdb->prefix . 'project_views';
    }

    public function registerHooks(): void
    {
        add_action('template_redirect', [$this, 'trackView']);
        add_filter('manage_project_posts_columns', [$this, 'addViewsColumn']);
        add_action('manage_project_posts_custom_column', [$this, 'renderViewsColumn'], 10, 2);
    }

    /**
     * Called on plugin activation (dbDelta creates or updates the table).
     */
    public function createTable(): void
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->tableName} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            project_id bigint(20) unsigned NOT NULL,
            viewed_at datetime NOT NULL,
            PRIMARY KEY  (id),
            KEY project_id (project_id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta($sql);
    }

    /**
     * INSERT: one row each time a visitor opens a project.
     */
    public function recordView(int $projectId): void
    {
        global $wpdb;

        $result = $wpdb->insert(
            $this->tableName,
            [
                'project_id' => $projectId,
                'viewed_at'  => current_time('mysql'),
            ],
            ['%d', '%s']
        );

        if (false === $result) {
            error_log('Project view not saved: ' . $wpdb->last_error);
        }
    }

    public function trackView(): void
    {
        // Count visitors only, not editors or previews.
        if (!is_singular('project') || is_preview() || current_user_can('edit_projects')) {
            return;
        }

        $this->recordView(get_queried_object_id());
    }

    /**
     * SELECT: views for one project, cached for one hour.
     */
    public function getViewCount(int $projectId): int
    {
        global $wpdb;

        $key   = 'brightforge_project_views_count_' . $projectId;
        $count = get_transient($key);

        if (false !== $count) {
            return (int) $count;
        }

        $count = (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->tableName} WHERE project_id = %d",
                $projectId
            )
        );

        // An empty result is not cached, so the first views show up at once.
        if ($count > 0) {
            set_transient($key, $count, HOUR_IN_SECONDS);
        }

        return $count;
    }

    /**
     * SELECT: most viewed projects, cached for one hour.
     */
    public function getMostViewed(int $limit = 5): array
    {
        global $wpdb;

        $limit = max(1, min(20, $limit));
        $key   = 'brightforge_project_views_top_' . $limit;
        $rows  = get_transient($key);

        if (false !== $rows) {
            return $rows;
        }

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT project_id, COUNT(*) AS views
                 FROM {$this->tableName}
                 GROUP BY project_id
                 ORDER BY views DESC
                 LIMIT %d",
                $limit
            )
        ) ?: [];

        if ($rows) {
            set_transient($key, $rows, HOUR_IN_SECONDS);
        }

        return $rows;
    }

    /**
     * Same as getMostViewed(). Kept so older calls keep working.
     */
    public function getMostViewedProjects(int $count = 5): array
    {
        return $this->getMostViewed($count);
    }

    /**
     * Show the numbers in Projects > All Projects.
     */
    public function addViewsColumn(array $columns): array
    {
        $columns['project_views'] = 'Views';

        return $columns;
    }

    public function renderViewsColumn(string $column, int $postId): void
    {
        if ('project_views' === $column) {
            echo esc_html((string) $this->getViewCount($postId));
        }
    }
}