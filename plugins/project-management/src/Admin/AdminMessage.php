<?php

namespace Brightforge\ProjectManager\Admin;

/**
 * Configurable message shown on the WordPress Dashboard.
 *
 * Its two settings are saved with the Project Settings form
 * (option group "brightforge_project_settings").
 */
class AdminMessage
{
    public const OPTION_ENABLED = 'brightforge_admin_message_enabled';
    public const OPTION_TEXT    = 'brightforge_admin_message_text';
    public const DEFAULT_TEXT   = 'Welcome to the Project Management Toolkit!';

    public function registerHooks(): void
    {
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('admin_notices', [$this, 'displayNotice']);
    }

    /**
     * Called on plugin activation. add_option() never overwrites a saved value.
     */
    public static function setDefaults(): void
    {
        add_option(self::OPTION_ENABLED, 0);
        add_option(self::OPTION_TEXT, self::DEFAULT_TEXT);
    }

    public function registerSettings(): void
    {
        register_setting(
            'brightforge_project_settings',
            self::OPTION_ENABLED,
            [
                'sanitize_callback' => [$this, 'sanitizeEnabled'],
            ]
        );

        register_setting(
            'brightforge_project_settings',
            self::OPTION_TEXT,
            [
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        // The fields appear inside the existing Project Settings form
        // through do_settings_sections( 'project-settings' ).
        add_settings_section(
            'brightforge_admin_message_section',
            __('Dashboard Message', 'project-management-toolkit'),
            '__return_false',
            'project-settings'
        );

        add_settings_field(
            self::OPTION_ENABLED,
            __('Enable Message', 'project-management-toolkit'),
            [$this, 'renderEnabledField'],
            'project-settings',
            'brightforge_admin_message_section'
        );

        add_settings_field(
            self::OPTION_TEXT,
            __('Message', 'project-management-toolkit'),
            [$this, 'renderTextField'],
            'project-settings',
            'brightforge_admin_message_section'
        );
    }

    /**
     * An unchecked box sends nothing, so anything empty becomes 0.
     */
    public function sanitizeEnabled($value): int
    {
        return empty($value) ? 0 : 1;
    }

    public function renderEnabledField(): void
    {
        ?>
        <label>
            <input
                type="checkbox"
                name="<?php echo esc_attr(self::OPTION_ENABLED); ?>"
                value="1"
                <?php checked(1, (int) get_option(self::OPTION_ENABLED, 0)); ?>
            >
            <?php esc_html_e('Show the message on the Dashboard', 'project-management-toolkit'); ?>
        </label>
        <?php
    }

    public function renderTextField(): void
    {
        ?>
        <input
            type="text"
            name="<?php echo esc_attr(self::OPTION_TEXT); ?>"
            value="<?php echo esc_attr(get_option(self::OPTION_TEXT, self::DEFAULT_TEXT)); ?>"
            class="regular-text"
        >
        <?php
    }

    public function displayNotice(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $screen = get_current_screen();

        if (!$screen || 'dashboard' !== $screen->id) {
            return;
        }

        if (!get_option(self::OPTION_ENABLED, 0)) {
            return;
        }

        $message = get_option(self::OPTION_TEXT, '');

        if ('' === $message) {
            return;
        }
        ?>
        <div class="notice notice-info is-dismissible">
            <p><?php echo esc_html($message); ?></p>
        </div>
        <?php
    }
}
