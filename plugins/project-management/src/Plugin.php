<?php

namespace Harish\ProjectManager;

use Harish\ProjectManager\PostTypes\ProjectPostType;
use Harish\ProjectManager\Taxonomies\ProjectTaxonomy;
use Harish\ProjectManager\Admin\JavaProjectsPage;
use Harish\ProjectManager\Admin\SettingsPage;
use Harish\ProjectManager\Admin\AdminMessage;
use Harish\ProjectManager\Admin\ProjectMeta;
use Harish\ProjectManager\Database\ProjectViews;
use Harish\ProjectManager\Api\ProjectController;
use Harish\ProjectManager\Api\AjaxController;
use Harish\ProjectManager\Support\ProjectRoles;

class Plugin
{
    private ProjectPostType $projectPostType;
    private ProjectTaxonomy $projectTaxonomy;
    private JavaProjectsPage $javaProjectsPage;
    private SettingsPage $settingsPage;
    private AdminMessage $adminMessage;
    private ProjectMeta $projectMeta;
    private ProjectViews $projectViews;
    private AjaxController $ajaxController;
    private ProjectController $projectController;
    private ProjectRoles $projectRoles;

    public function __construct()
    {
        $this->projectPostType = new ProjectPostType();
        $this->projectTaxonomy = new ProjectTaxonomy();
        $this->javaProjectsPage = new JavaProjectsPage();
        $this->settingsPage = new SettingsPage();
        $this->adminMessage = new AdminMessage();
        $this->projectMeta = new ProjectMeta();
        $this->projectViews = new ProjectViews();
        $this->projectController = new ProjectController();
        $this->ajaxController = new AjaxController();
        $this->projectRoles = new ProjectRoles();
    }

    public function boot(): void
    {
        $this->projectPostType->registerHooks();
        $this->projectTaxonomy->registerHooks();
        $this->javaProjectsPage->registerHooks();
        $this->settingsPage->registerHooks();
        $this->adminMessage->registerHooks();
        $this->projectMeta->registerHooks();
        $this->projectViews->registerHooks();
        $this->projectController->registerHooks();
        $this->ajaxController->registerHooks();
        $this->projectRoles->registerHooks();
    }
}