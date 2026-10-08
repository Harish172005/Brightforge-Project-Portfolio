<?php

namespace Brightforge\ProjectManager\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class ProjectMeta
{
    public const STATUSES = ['Planned', 'In Progress', 'Completed'];

    public function registerHooks(): void
    {
        add_action(
            'add_meta_boxes',
            [$this, 'registerMetaBox']
        );

        add_action(
            'save_post_project',
            [$this, 'saveMeta']
        );
    }

    public function registerMetaBox(): void
    {
        add_meta_box(
            'brightforge_project_details',
            'Project Details',
            [$this, 'renderMetaBox'],
            'project',
            'normal',
            'high'
        );
    }

    public function renderMetaBox($post): void
    {
        $project_url = get_post_meta($post->ID, '_brightforge_project_url', true);
        $status      = get_post_meta($post->ID, '_brightforge_project_status', true);
        $completed   = get_post_meta($post->ID, '_brightforge_project_completed', true);

        wp_nonce_field(
            'brightforge_project_save',
            'brightforge_project_nonce'
        );
        ?>

        <p>
            <label for="brightforge_project_url">
                Project URL:
            </label>
        </p>

        <input
            type="url"
            id="brightforge_project_url"
            name="brightforge_project_url"
            value="<?php echo esc_attr($project_url); ?>"
            class="widefat"
            placeholder="https://example.com"
        >

        <p>
            <label for="brightforge_project_status">
                Status:
            </label>
        </p>

        <select id="brightforge_project_status" name="brightforge_project_status">
            <?php foreach (self::STATUSES as $option) : ?>
                <option
                    value="<?php echo esc_attr($option); ?>"
                    <?php selected($status, $option); ?>
                >
                    <?php echo esc_html($option); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <p>
            <label for="brightforge_project_completed">
                Completion date:
            </label>
        </p>

        <input
            type="date"
            id="brightforge_project_completed"
            name="brightforge_project_completed"
            value="<?php echo esc_attr($completed); ?>"
        >

        <?php
    }

    public function saveMeta(int $post_id): void
    {
        /*
         * Verify nonce.
         */
        if (
            !isset($_POST['brightforge_project_nonce']) ||
            !wp_verify_nonce(
                sanitize_text_field(wp_unslash($_POST['brightforge_project_nonce'])),
                'brightforge_project_save'
            )
        ) {
            return;
        }

        /*
         * Ignore autosaves.
         */
        if (
            defined('DOING_AUTOSAVE') &&
            DOING_AUTOSAVE
        ) {
            return;
        }

        /*
         * Check user capability.
         */
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        /*
         * Project URL: web addresses only (http or https).
         */
        if (isset($_POST['brightforge_project_url'])) {
            update_post_meta(
                $post_id,
                '_brightforge_project_url',
                esc_url_raw(
                    wp_unslash($_POST['brightforge_project_url']),
                    ['http', 'https']
                )
            );
        }

        /*
         * Status: only accept one of the allowed values.
         */
        if (isset($_POST['brightforge_project_status'])) {
            $status = sanitize_text_field(
                wp_unslash($_POST['brightforge_project_status'])
            );

            if (in_array($status, self::STATUSES, true)) {
                update_post_meta($post_id, '_brightforge_project_status', $status);
            }
        }

        /*
         * Completion date: must be a real Y-m-d date, or empty.
         */
        if (isset($_POST['brightforge_project_completed'])) {
            $date   = sanitize_text_field(
                wp_unslash($_POST['brightforge_project_completed'])
            );
            $parsed = \DateTime::createFromFormat('Y-m-d', $date);

            if ($date === '' || ($parsed && $parsed->format('Y-m-d') === $date)) {
                update_post_meta($post_id, '_brightforge_project_completed', $date);
            }
        }
    }
}
