<?php

namespace Brightforge\ProjectManager\Shortcodes;

use Brightforge\ProjectManager\Admin\ProjectMeta;
use Brightforge\ProjectManager\Support\ProjectQuery;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * [projects industry="fintech" technology="php" status="Completed" ids="12,15" limit="6" filters="yes"]
 */
class ProjectsShortcode
{
    public function registerHooks(): void
    {
        add_shortcode('projects', [$this, 'render']);
    }

    public function render($atts): string
    {
        $atts = shortcode_atts(
            [
                'industry'   => '',
                'technology' => '',
                'status'     => '',
                'ids'        => '',
                'limit'      => '',
                'filters'    => 'no',
            ],
            $atts,
            'projects'
        );

        $with_filters = 'yes' === $atts['filters'];

        $raw = [
            'industry'   => $atts['industry'],
            'technology' => $atts['technology'],
            'status'     => $atts['status'],
            'ids'        => $atts['ids'],
            'per_page'   => '' !== $atts['limit']
                ? $atts['limit']
                : get_option('brightforge_projects_per_page', 6),
        ];

        if ($with_filters) {
            // Filters are read from the URL. They only choose what to show and
            // change nothing on the server, so no nonce is needed.
            // phpcs:disable WordPress.Security.NonceVerification.Recommended
            foreach (['industry', 'technology', 'status'] as $key) {
                if (isset($_GET[$key])) {
                    $raw[$key] = $_GET[$key];
                }
            }

            $raw['search'] = $_GET['q'] ?? '';
            $raw['page']   = $_GET['pg'] ?? 1;
            // phpcs:enable
        }

        $filters = ProjectQuery::sanitizeFilters($raw);
        $query   = ProjectQuery::run($filters);

        ob_start();

        echo '<div class="project-listing">';

        if ($with_filters) {
            $this->renderFilters($filters);
        }

        if ($query->have_posts()) {
            echo '<div class="grid">';

            while ($query->have_posts()) {
                $query->the_post();
                $this->renderCard();
            }

            echo '</div>';

            if ($with_filters) {
                $this->renderPagination($query, $filters['page']);
            }
        } else {
            echo '<p>' . esc_html__('No projects match those filters.', 'project-management-toolkit') . '</p>';
        }

        echo '</div>';

        // Required after a custom query, so the rest of the page is not affected.
        wp_reset_postdata();

        return (string) ob_get_clean();
    }

    private function renderCard(): void
    {
        $terms    = get_the_terms(get_the_ID(), 'project_type');
        $industry = ($terms && !is_wp_error($terms)) ? $terms[0] : null;
        $status   = (string) get_post_meta(get_the_ID(), '_brightforge_project_status', true);
        $class    = $industry ? 'industry-' . sanitize_html_class($industry->slug) : 'industry-default';
        ?>
        <article class="card <?php echo esc_attr($class); ?>">
            <?php if (has_post_thumbnail()) : ?>
                <a class="card__image" href="<?php echo esc_url(get_permalink()); ?>" tabindex="-1" aria-hidden="true">
                    <?php echo get_the_post_thumbnail(get_the_ID(), 'medium'); ?>
                </a>
            <?php endif; ?>
            <div class="card__body">
                <p class="card__meta">
                    <?php if ($industry) : ?>
                        <span class="badge"><?php echo esc_html($industry->name); ?></span>
                    <?php endif; ?>
                    <?php if ('' !== $status) : ?>
                        <span class="status"><?php echo esc_html($status); ?></span>
                    <?php endif; ?>
                </p>
                <h3 class="card__title">
                    <a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a>
                </h3>
                <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
            </div>
        </article>
        <?php
    }

    private function renderFilters(array $filters): void
    {
        $industries   = $this->options(get_terms(['taxonomy' => 'project_type', 'hide_empty' => true]));
        $technologies = $this->options(get_terms(['taxonomy' => 'technologies', 'hide_empty' => true]));
        $statuses     = array_combine(ProjectMeta::STATUSES, ProjectMeta::STATUSES);
        ?>
        <form class="filters" method="get" role="search">
            <label class="filters__field filters__field--search">
                <span><?php esc_html_e('Search', 'project-management-toolkit'); ?></span>
                <input type="search" name="q" value="<?php echo esc_attr($filters['search']); ?>">
            </label>
            <?php
            $this->renderSelect('industry', __('Industry', 'project-management-toolkit'), $industries, $filters['industry']);
            $this->renderSelect('technology', __('Technology', 'project-management-toolkit'), $technologies, $filters['technology']);
            $this->renderSelect('status', __('Status', 'project-management-toolkit'), $statuses, $filters['status']);
            ?>
            <button type="submit" class="button"><?php esc_html_e('Filter', 'project-management-toolkit'); ?></button>
            <a class="filters__reset" href="<?php echo esc_url(remove_query_arg(['industry', 'technology', 'status', 'q', 'pg'])); ?>">
                <?php esc_html_e('Reset', 'project-management-toolkit'); ?>
            </a>
        </form>
        <?php
    }

    private function renderSelect(string $name, string $label, array $options, string $selected): void
    {
        ?>
        <label class="filters__field">
            <span><?php echo esc_html($label); ?></span>
            <select name="<?php echo esc_attr($name); ?>">
                <option value=""><?php esc_html_e('All', 'project-management-toolkit'); ?></option>
                <?php foreach ($options as $value => $text) : ?>
                    <option value="<?php echo esc_attr($value); ?>" <?php selected($selected, $value); ?>>
                        <?php echo esc_html($text); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <?php
    }

    private function renderPagination(WP_Query $query, int $current): void
    {
        if ($query->max_num_pages < 2) {
            return;
        }

        echo '<nav class="pagination" aria-label="' . esc_attr__('Projects pages', 'project-management-toolkit') . '">';
        echo wp_kses_post(
            paginate_links([
                'base'    => add_query_arg('pg', '%#%'),
                'format'  => '',
                'current' => $current,
                'total'   => (int) $query->max_num_pages,
            ])
        );
        echo '</nav>';
    }

    private function options($terms): array
    {
        return ($terms && !is_wp_error($terms)) ? wp_list_pluck($terms, 'name', 'slug') : [];
    }
}