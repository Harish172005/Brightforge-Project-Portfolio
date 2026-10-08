<?php

namespace Brightforge\ProjectManager\Api;

use Brightforge\ProjectManager\Support\ProjectData;
use WP_Error;
use WP_Query;
use WP_REST_Request;
use WP_REST_Response;

if (!defined('ABSPATH')) {
    exit;
}

class ProjectController
{
    public function registerHooks(): void
    {
        add_action(
            'rest_api_init',
            [$this, 'registerRoutes']
        );
    }

    public function registerRoutes(): void
    {
        register_rest_route(
            'brightforge/v1',
            '/projects',
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'getProjects'],

                // Public, read-only data (published projects only).
                'permission_callback' => '__return_true',

                'args'                => [
                    'page'     => [
                        'type'              => 'integer',
                        'default'           => 1,
                        'minimum'           => 1,
                        'validate_callback' => 'rest_validate_request_arg',
                        'sanitize_callback' => 'rest_sanitize_request_arg',
                    ],
                    'per_page' => [
                        'type'              => 'integer',
                        'default'           => 6,
                        'minimum'           => 1,
                        'maximum'           => 50,
                        'validate_callback' => 'rest_validate_request_arg',
                        'sanitize_callback' => 'rest_sanitize_request_arg',
                    ],
                ],
            ]
        );
    }

    /**
     * @return WP_REST_Response|WP_Error
     */
    public function getProjects(WP_REST_Request $request)
    {
        $page    = (int) $request->get_param('page');
        $perPage = (int) $request->get_param('per_page');

        $query = new WP_Query([
            'post_type'      => 'project',
            'post_status'    => 'publish',
            'posts_per_page' => $perPage,
            'paged'          => $page,
        ]);

        if ($page > max(1, (int) $query->max_num_pages)) {
            wp_reset_postdata();

            return new WP_Error(
                'brightforge_page_out_of_range',
                'The requested page does not exist.',
                ['status' => 404]
            );
        }

        $projects = [];

        while ($query->have_posts()) {
            $query->the_post();
            $projects[] = ProjectData::fromLoop();
        }

        wp_reset_postdata();

        $response = new WP_REST_Response(
            [
                'projects' => $projects,
                'page'     => $page,
                'per_page' => $perPage,
                'total'    => (int) $query->found_posts,
                'pages'    => (int) $query->max_num_pages,
            ],
            200
        );

        $response->header('X-WP-Total', (string) $query->found_posts);
        $response->header('X-WP-TotalPages', (string) $query->max_num_pages);

        return $response;
    }
}
