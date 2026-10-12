<?php

namespace Brightforge\ProjectManager\Widgets;

use Brightforge\ProjectManager\Database\ProjectViews;
use WP_Widget;

if (!defined('ABSPATH')) {
    exit;
}

class MostViewedWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'brightforge_most_viewed_projects',
            'Most Viewed Projects',
            [
                'description' => 'Lists the projects visitors open most often.'
            ]
        );
    }

    public static function register(): void
    {
        error_log('MostViewedWidget registered');

        register_widget(self::class);
    }

    public function widget($args, $instance): void
    {
        $title = $instance['title'] ?? 'Most viewed projects';
        $count = isset($instance['count'])
            ? absint($instance['count'])
            : 5;

        $rows = (new ProjectViews())->getMostViewedProjects($count);

        echo $args['before_widget'];

        echo $args['before_title']
            . esc_html($title)
            . $args['after_title'];

        $items = '';

        foreach ($rows as $row) {
            $post = get_post((int) $row->project_id);

            if (!$post || 'publish' !== $post->post_status) {
                continue;
            }

            $items .= sprintf(
                '<li><a href="%s">%s</a> (%s)</li>',
                esc_url(get_permalink($post)),
                esc_html(get_the_title($post)),
                esc_html(number_format_i18n((int) $row->views))
            );
        }

        if ('' === $items) {
            echo '<p>' .
                esc_html__(
                    'No project views yet.',
                    'project-management-toolkit'
                ) .
                '</p>';
        } else {
            echo '<ul>' . $items . '</ul>';
        }

        echo $args['after_widget'];
    }

    public function form($instance): void
    {
        $title = $instance['title'] ?? 'Most viewed projects';
        $count = $instance['count'] ?? 5;
        ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                Title:
            </label>

            <input
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                type="text"
                value="<?php echo esc_attr($title); ?>"
            >
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('count')); ?>">
                Number of projects (1 to 10):
            </label>

            <input
                class="tiny-text"
                id="<?php echo esc_attr($this->get_field_id('count')); ?>"
                name="<?php echo esc_attr($this->get_field_name('count')); ?>"
                type="number"
                min="1"
                max="10"
                value="<?php echo esc_attr($count); ?>"
            >
        </p>

        <?php
    }

    public function update($new_instance, $old_instance): array
    {
        return [
            'title' => sanitize_text_field(
                $new_instance['title'] ?? ''
            ),
            'count' => min(
                10,
                max(1, absint($new_instance['count'] ?? 5))
            ),
        ];
    }
}