<?php

namespace Brightforge\ProjectManager\Api;

use Brightforge\ProjectManager\Support\ProjectData;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

class AjaxController
{
    public const ACTION       = 'brightforge_load_more_projects';
    public const NONCE_ACTION = 'brightforge_load_more';
    private const PER_PAGE    = 5;

    public function registerHooks(): void
    {
        add_action(
            'wp_ajax_' . self::ACTION,
            [$this, 'loadMoreProjects']
        );

        // Logged-out visitors need this one too.
        add_action(
            'wp_ajax_nopriv_' . self::ACTION,
            [$this, 'loadMoreProjects']
        );
    }

    /**
     * Values the front-end script needs. Pass them to the script with
     * wp_localize_script() when it is enqueued.
     */
    public function getScriptData(): array
    {
        return [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'action'  => self::ACTION,
            'nonce'   => wp_create_nonce(self::NONCE_ACTION),
        ];
    }

    public function loadMoreProjects(): void
    {
        // Stops the request (403) if the nonce is missing or wrong.
        check_ajax_referer(self::NONCE_ACTION, 'nonce');

        $page = isset($_POST['page'])
            ? max(1, absint(wp_unslash($_POST['page'])))
            : 1;

        $query = new WP_Query([
            'post_type'      => 'project',
            'post_status'    => 'publish',
            'posts_per_page' => self::PER_PAGE,
            'paged'          => $page,
        ]);

        $projects = [];

        while ($query->have_posts()) {
            $query->the_post();
            $projects[] = ProjectData::fromLoop();
        }

        wp_reset_postdata();

        wp_send_json([
            'projects' => $projects,
            'page'     => $page,
            'pages'    => (int) $query->max_num_pages,
        ]);
    }
}
