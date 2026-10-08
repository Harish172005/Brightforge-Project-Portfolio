<?php

namespace Brightforge\ProjectManager\Database;

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
        // The activation hook will be registered from the main plugin file.
    }

    public function createTable(): void
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->tableName} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            project_id bigint(20) unsigned NOT NULL,
            view_count bigint(20) unsigned NOT NULL DEFAULT 0,
            meta_value varchar(255) NOT NULL,
            created_at datetime NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta($sql);
    }

    public function getProjectViews(string $metaValue): array
    {
        global $wpdb;

        $query = $wpdb->prepare(
            "SELECT *
             FROM {$this->tableName}
             WHERE meta_value = %s",
            $metaValue
        );

        return $wpdb->get_results($query);
    }

    public function getMostViewedProjects(int $count = 5): array
    {
        global $wpdb;

        $count = min(10, max(1, $count));

        $query = $wpdb->prepare(
            "SELECT project_id, view_count AS views
             FROM {$this->tableName}
             ORDER BY view_count DESC
             LIMIT %d",
            $count
        );

        return $wpdb->get_results($query);
    }

    public function getCachedProjectViews(string $metaValue): array
    {
        $cacheKey = 'brightforge_project_views_' . md5($metaValue);

        $views = get_transient($cacheKey);

        if ($views !== false) {
            return $views;
        }

        $views = $this->getProjectViews($metaValue);

        set_transient(
            $cacheKey,
            $views,
            HOUR_IN_SECONDS
        );

        return $views;
    }
}