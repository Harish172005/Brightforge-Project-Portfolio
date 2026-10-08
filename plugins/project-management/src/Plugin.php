<?php

namespace Brightforge\ProjectManager;

use Brightforge\ProjectManager\PostTypes\ProjectPostType;
use Brightforge\ProjectManager\Taxonomies\ProjectTaxonomy;
use Brightforge\ProjectManager\Admin\JavaProjectsPage;
use Brightforge\ProjectManager\Admin\SettingsPage;
use Brightforge\ProjectManager\Admin\AdminMessage;
use Brightforge\ProjectManager\Admin\ProjectMeta;
use Brightforge\ProjectManager\Database\ProjectViews;
use Brightforge\ProjectManager\Api\ProjectController;
use Brightforge\ProjectManager\Api\AjaxController;
use Brightforge\ProjectManager\Support\ProjectRoles;
use Brightforge\ProjectManager\Widgets\MostViewedWidget;

class Plugin
{
    private ProjectPostType $projectPostType;
    private ProjectTaxonomy $projectTaxonomy;
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
        $this->settingsPage->registerHooks();
        $this->adminMessage->registerHooks();
        $this->projectMeta->registerHooks();
        $this->projectViews->registerHooks();
        $this->projectController->registerHooks();
        $this->ajaxController->registerHooks();
        $this->projectRoles->registerHooks();

        add_action('widgets_init', [MostViewedWidget::class, 'register']);
     }
}