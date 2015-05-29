<?php namespace Lasso\Actions\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Action Back-end Controller
 */
class Action extends Controller
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

        BackendMenu::setContext('Lasso.Actions', 'actions', 'action');
    }

    public function taken()
    {
        $this->pageTitle = "Actions taken";
        BackendMenu::setContext('Lasso.Actions', 'actions', 'taken');
        $this->addCss('/plugins/lasso/actions/assets/bootstrap/css/bootstrap.min.css');
        $actions = \Lasso\Actions\Models\Action::with('taken')->orderBy('is_active', 'desc')->orderBy('id', 'desc')->get();
        return $this->makePartial('taken', ['actions' => $actions]);
    }
}