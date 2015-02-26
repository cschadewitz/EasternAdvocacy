<?php namespace Lasso\Archive\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Lasso\Archive\ReportWidgets\TopViewed;

/**
 * Posts Back-end Controller
 */
class Posts extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Lasso.Archive', 'archive', 'posts');
        $topViewedWidget = new TopViewed($this);
        $topViewedWidget->alias = 'topViewed';
        $topViewedWidget->bindToController();
    }
}