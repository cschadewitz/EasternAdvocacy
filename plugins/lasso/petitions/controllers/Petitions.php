<?php
    namespace Lasso\Petitions\Controllers;

    use Backend\Facades\BackendMenu;

    class Petitions extends \Backend\Classes\Controller
    {
        public $requiredPermissions = ['lasso.petitions.access_petitions'];

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

            BackendMenu::setContext('Lasso.Petitions', 'petition', 'petitions');
        }


    }