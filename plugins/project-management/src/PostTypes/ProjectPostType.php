<?php

namespace Harish\ProjectManager\PostTypes;

class ProjectPostType
{
    public function registerHooks(): void
    {
        add_action('init', [$this, 'register']);
    }

    public function register(): void
    {
        register_post_type('project', [
            'labels' => [
                'name'          => 'Projects',
                'singular_name' => 'Project',
                'add_new'       => 'Add New',
                'add_new_item'  => 'Add New Project',
                'edit_item'     => 'Edit Project',
                'new_item'      => 'New Project',
                'view_item'     => 'View Project',
                'search_items'  => 'Search Projects',
            ],

            'capability_type' => ['project', 'projects'],
            'map_meta_cap'    => true,

            'public'       => true,
            'show_ui'      => true,
            'show_in_menu' => true,

            'supports' => [
                'title',
                'editor',
                'thumbnail',
            ],

            'has_archive' => true,

            'rewrite' => [
                'slug' => 'projects',
            ],
        ]);
    }
}