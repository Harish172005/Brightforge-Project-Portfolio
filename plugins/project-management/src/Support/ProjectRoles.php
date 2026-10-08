<?php

namespace Harish\ProjectManager\Support;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Project capabilities and the "Project Manager" role.
 *
 * Administrators and Editors can manage projects. The Project Manager role
 * can manage projects and upload images, and nothing else. Authors and
 * Contributors cannot touch projects.
 */
class ProjectRoles
{
    public const ROLE            = 'project_manager';
    private const VERSION        = '1';
    private const VERSION_OPTION = 'harish_roles_version';

    public static function capabilities(): array
    {
        return [
            'edit_projects',
            'edit_others_projects',
            'edit_published_projects',
            'edit_private_projects',
            'publish_projects',
            'read_private_projects',
            'delete_projects',
            'delete_others_projects',
            'delete_published_projects',
            'delete_private_projects',
        ];
    }

    public function registerHooks(): void
    {
        // Runs on every request but only does work once per version, so a
        // site that already has the plugin active gets the roles without
        // having to deactivate and reactivate it.
        add_action('init', [$this, 'maybeInstall'], 5);
    }

    public function maybeInstall(): void
    {
        if (self::VERSION !== get_option(self::VERSION_OPTION)) {
            self::install();
        }
    }

    public static function install(): void
    {
        $capabilities = self::capabilities();

        foreach (['administrator', 'editor'] as $role_name) {
            $role = get_role($role_name);

            if (!$role) {
                continue;
            }

            foreach ($capabilities as $capability) {
                $role->add_cap($capability);
            }
        }

        $role = get_role(self::ROLE);

        if (!$role) {
            $role = add_role(
                self::ROLE,
                'Project Manager',
                [
                    'read'         => true,
                    'upload_files' => true,
                ]
            );
        }

        if ($role) {
            foreach ($capabilities as $capability) {
                $role->add_cap($capability);
            }
        }

        update_option(self::VERSION_OPTION, self::VERSION);
    }

    public static function uninstall(): void
    {
        foreach (['administrator', 'editor'] as $role_name) {
            $role = get_role($role_name);

            if (!$role) {
                continue;
            }

            foreach (self::capabilities() as $capability) {
                $role->remove_cap($capability);
            }
        }

        remove_role(self::ROLE);
        delete_option(self::VERSION_OPTION);
    }
}
