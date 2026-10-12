<?php

namespace Brightforge\ProjectManager\Admin;

class SettingsPage
{
    public function registerHooks(): void
    {
        add_action('admin_menu', [$this, 'registerMenu']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function registerMenu(): void
    {
        add_submenu_page(
            'edit.php?post_type=project',
            'Project Settings',
            'Project Settings',
            'manage_options',
            'project-settings',
            [$this, 'render']
        );
    }

    public function registerSettings(): void
    {
        register_setting(
            'brightforge_project_settings',
            'brightforge_projects_per_page',
            [
                'sanitize_callback' => [$this, 'sanitizePerPage'],
            ]
        );

        register_setting(
            'brightforge_project_settings',
            'brightforge_default_project_status',
            [
                'sanitize_callback' => [$this, 'sanitizeDefaultStatus'],
            ]
        );
    }

    /**
     * Keep the number between 1 and 50.
     */
    public function sanitizePerPage($value): int
    {
        return min(50, max(1, absint($value)));
    }

    /**
     * Only accept a value from the dropdown.
     */
    public function sanitizeDefaultStatus($value): string
    {
        $value = sanitize_key($value);

        return in_array($value, ['publish', 'draft'], true) ? $value : 'publish';
    }

    public function render(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die('You do not have permission to access this page.');
        }
        ?>

        <div class="wrap">

            <h1>Project Settings</h1>

            <form method="post" action="options.php">

                <?php settings_fields('brightforge_project_settings'); ?>

                <table class="form-table">

                    <tr>
                        <th scope="row">
                            <label for="brightforge_projects_per_page">
                                Projects Per Page
                            </label>
                        </th>

                        <td>
                            <input
                                type="number"
                                id="brightforge_projects_per_page"
                                name="brightforge_projects_per_page"
                                value="<?php echo esc_attr(
                                    get_option('brightforge_projects_per_page', 10)
                                ); ?>"
                                min="1"
                                max="50"
                            >
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="brightforge_default_project_status">
                                Default Project Status
                            </label>
                        </th>

                        <td>
                            <select
                                id="brightforge_default_project_status"
                                name="brightforge_default_project_status"
                            >

                                <option
                                    value="publish"
                                    <?php selected(
                                        get_option(
                                            'brightforge_default_project_status',
                                            'publish'
                                        ),
                                        'publish'
                                    ); ?>
                                >
                                    Published
                                </option>

                                <option
                                    value="draft"
                                    <?php selected(
                                        get_option(
                                            'brightforge_default_project_status',
                                            'publish'
                                        ),
                                        'draft'
                                    ); ?>
                                >
                                    Draft
                                </option>

                            </select>
                        </td>
                    </tr>

                </table>

                <?php do_settings_sections('project-settings'); ?>

                <?php submit_button('Save Settings'); ?>

            </form>

        </div>

        <?php
    }
}