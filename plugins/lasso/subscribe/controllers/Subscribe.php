<?php
namespace Lasso\Subscribe\Controllers;

use Backend\Facades\BackendMenu;

class Subscribe extends \Backend\Classes\Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    public $requiredPermissions = ['lasso.subscribe.*'];

    public $formConfig = 'form_config.yaml';

    public $listConfig = 'list_config.yaml';

    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('RainLab.User', 'user', 'subscribers');
    }
}