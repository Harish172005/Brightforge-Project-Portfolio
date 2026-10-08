<?php

namespace Harish\ProjectManager\Support;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * One place that turns the project in the Loop into a plain array.
 *
 * Used by the REST endpoint and the AJAX handler so both return the same
 * fields, and so any output rule is fixed in a single file.
 */
class ProjectData
{
    public static function fromLoop(): array
    {
        $id         = get_the_ID();
        $categories = get_the_terms($id, 'project_type');
        $github_url = function_exists('get_field') ? get_field('github_url') : '';

        return [
            'id'            => $id,
            'title'         => self::plainText(get_the_title()),
            'description'   => self::plainText(get_the_excerpt()),
            'category'      => ($categories && !is_wp_error($categories))
                ? wp_list_pluck($categories, 'name')
                : [],
            'status'        => (string) get_post_meta($id, '_harish_project_status', true),
            'image'         => (string) get_the_post_thumbnail_url($id, 'medium'),
            'wordpress_url' => get_permalink($id),
            'project_url'   => esc_url_raw(
                (string) get_post_meta($id, '_harish_project_url', true)
            ),
            'github_url'    => $github_url ? esc_url_raw($github_url) : '',
        ];
    }

    private static function plainText(string $text): string
    {
        return html_entity_decode(
            wp_strip_all_tags($text),
            ENT_QUOTES,
            'UTF-8'
        );
    }
}
