<?php
/**
 * Runs only when the plugin is deleted from the Plugins screen.
 *
 * WordPress loads this file without the plugin, so it does not use the
 * autoloader. It removes the plugin's options, cached views and custom table.
 * Projects (posts) and their meta are left in place, because they are content.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

// Roles and capabilities added by the plugin.
require_once __DIR__ . '/src/Support/ProjectRoles.php';
\Brightforge\ProjectManager\Support\ProjectRoles::uninstall();

// Options.
$options = [
    'brightforge_admin_message_enabled',
    'brightforge_admin_message_text',
    'brightforge_projects_per_page',
    'brightforge_default_project_status',
];

foreach ($options as $option) {
    delete_option($option);
}

// Cached project views (transients are stored as options).
$wpdb->query(
    $wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
        $wpdb->esc_like('_transient_brightforge_project_views_') . '%',
        $wpdb->esc_like('_transient_timeout_brightforge_project_views_') . '%'
    )
);

// Custom table. The name is built from the trusted table prefix, not user input.
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}project_views");
