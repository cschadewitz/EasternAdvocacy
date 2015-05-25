<?php
namespace Lasso\Subscribe\Controllers;

use Backend\Facades\BackendMenu;
use Backend\Facades\BackendAuth;

class Subscribe extends \Backend\Classes\Controller
{

    public $implement = ['Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    public $formConfig = 'form_config.yaml';

    public $listConfig = 'list_config.yaml';

    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Lasso.Subscribe', 'subscribe', 'subscribe');
    }

    public function onDelete(){
        $subscribers = post("subscriber");
        foreach($subscribers as $sub){
            if($sub != "on"){
                \Lasso\Subscribe\Models\Subscribe::find($sub)->delete();
            }
        }
        \Flash::success('Subscriber(s) Successfully deleted.');
        return $this->listRefresh();
    }
}