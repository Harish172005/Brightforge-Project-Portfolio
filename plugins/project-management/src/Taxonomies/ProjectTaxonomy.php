<?php

namespace Brightforge\ProjectManager\Taxonomies;

class ProjectTaxonomy
{
    public function registerHooks(): void
    {
        add_action('init', [$this, 'register']);
    }

    public function register(): void
    {
        $this->registerTechnologies();
        $this->registerProjectType();
    }

    private function registerTechnologies(): void
    {
        register_taxonomy(
            'technologies',
            ['project'],
            [
                'labels' => [
                    'name'          => 'Technologies',
                    'singular_name' => 'Technology',
                ],

                'public'       => true,
                'show_ui'      => true,
                'show_in_menu' => true,

                'hierarchical' => true,

                'capabilities' => [
                    'assign_terms' => 'edit_projects',
                ],

                'rewrite' => [
                    'slug' => 'technologies',
                ],
            ]
        );
    }

    private function registerProjectType(): void
    {
        register_taxonomy(
            'project_type',
            ['project'],
            [
                'labels' => [
                    'name'          => 'Project Types',
                    'singular_name' => 'Project Type',
                ],

                'public'       => true,
                'show_ui'      => true,
                'show_in_menu' => true,

                'hierarchical' => true,

                'capabilities' => [
                    'assign_terms' => 'edit_projects',
                ],

                'rewrite' => [
                    'slug' => 'project-types',
                ],
            ]
        );
    }
}