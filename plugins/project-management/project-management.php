<?php
/*
Plugin Name: Project Management
Description: OOP-based project management plugin.
Version: 2.0.0
Author: Harish
*/

if (!defined('ABSPATH')) {
    exit;
}

/*
 * Load Composer's autoloader.
 */
require_once __DIR__ . '/vendor/autoload.php';

use Brightforge\ProjectManager\Plugin;

/*
 * Start the plugin.
 */
$plugin = new Plugin();
$plugin->boot();


use Brightforge\ProjectManager\Database\ProjectViews;

register_activation_hook(
    __FILE__,
    function () {
        $projectViews = new ProjectViews();
        $projectViews->createTable();
    }
);