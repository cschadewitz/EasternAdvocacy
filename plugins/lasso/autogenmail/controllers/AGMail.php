<?php namespace Lasso\AutoGenMail\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * A G Mail Back-end Controller
 */
class AGMail extends Controller
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

        BackendMenu::setContext('Lasso.AutoGenMail', 'autogenmail', 'agmail');
    }
}