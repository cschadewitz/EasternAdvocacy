<?php namespace Lasso\Archive\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Lasso\Archive\ReportWidgets\TopViewed;
use Lasso\Archive\Models\Emails;

/**
 * Posts Back-end Controller
 */
class Posts extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $popularPost;

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();
        $this->assignVars();
        $this->vars['postsTotal'] = Emails::count();
        $this->vars['popularPost'] = $this->popularPost;
        BackendMenu::setContext('Lasso.Archive', 'archive');
        $topViewedWidget = new TopViewed($this);
        $topViewedWidget->alias = 'topViewed';
        $topViewedWidget->bindToController();
    }

    public function assignVars()
    {
        $this->popularPost = Emails::orderBy('views', 'desc')->take(1)->get();

    }
}