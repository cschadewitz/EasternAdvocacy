<?php namespace \Lasso\ZipLookup\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * ZipLookup Back-end Controller
 */
class ZipLookup extends Controller
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

        BackendMenu::setContext('.Lasso\ZipLookup', 'lasso\ziplookup', 'ziplookup');
    }
}