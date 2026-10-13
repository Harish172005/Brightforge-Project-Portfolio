<?php

namespace Brightforge\ProjectManager\Support;

use Brightforge\ProjectManager\Admin\ProjectMeta;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Builds and runs the WP_Query for project listings.
 * Industry, technology, status and search are handled here, in one place.
 */
class ProjectQuery
{
    public const MAX_PER_PAGE = 50;

    /**
     * Turn raw input (URL values or shortcode attributes) into safe filters.
     * Anything unexpected becomes an empty value and is simply not applied.
     */
    public static function sanitizeFilters(array $raw): array
    {
        $raw    = wp_unslash($raw);
        $status = self::text($raw, 'status');
        $per_page = absint(self::text($raw, 'per_page'));

        return [
            'industry'   => sanitize_title(self::text($raw, 'industry')),
            'technology' => sanitize_title(self::text($raw, 'technology')),
            'status'     => in_array($status, ProjectMeta::STATUSES, true) ? $status : '',
            'search'     => sanitize_text_field(self::text($raw, 'search')),
            'ids'        => array_values(array_filter(array_map('absint', explode(',', self::text($raw, 'ids'))))),
            'page'       => max(1, absint(self::text($raw, 'page'))),
            'per_page'   => min(self::MAX_PER_PAGE, $per_page > 0 ? $per_page : 6),
        ];
    }

    /**
     * The arguments for WP_Query. Kept separate so they can be tested.
     */
    public static function buildArgs(array $filters): array
    {
        $args = [
            'post_type'      => 'project',
            'post_status'    => 'publish',
            'posts_per_page' => $filters['per_page'],
            'paged'          => $filters['page'],
        ];

        if ('' !== $filters['search']) {
            $args['s'] = $filters['search'];
        }

        if ($filters['ids']) {
            $args['post__in'] = $filters['ids'];
            $args['orderby']  = 'post__in';
        }

        $taxonomies = [];

        if ('' !== $filters['industry']) {
            $taxonomies[] = [
                'taxonomy' => 'project_type',
                'field'    => 'slug',
                'terms'    => $filters['industry'],
            ];
        }

        if ('' !== $filters['technology']) {
            $taxonomies[] = [
                'taxonomy' => 'technologies',
                'field'    => 'slug',
                'terms'    => $filters['technology'],
            ];
        }

        if ($taxonomies) {
            $args['tax_query'] = array_merge(['relation' => 'AND'], $taxonomies);
        }

        if ('' !== $filters['status']) {
            $args['meta_query'] = [
                [
                    'key'   => '_brightforge_project_status',
                    'value' => $filters['status'],
                ],
            ];
        }

        return $args;
    }

    public static function run(array $filters): WP_Query
    {
        return new WP_Query(self::buildArgs($filters));
    }

    /**
     * A plain string from the input, or '' for anything else (such as ?industry[]=x).
     */
    private static function text(array $raw, string $key): string
    {
        return isset($raw[$key]) && is_scalar($raw[$key]) ? (string) $raw[$key] : '';
    }
}