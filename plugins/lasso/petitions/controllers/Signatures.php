<?php
    namespace Lasso\Petitions\Controllers;

    use Backend\Facades\BackendMenu;

    class Signatures extends \Backend\Classes\Controller
    {
        public $requiredPermissions = ['lasso.petitions.access_signatures'];

        public $implement = ['Backend.Behaviors.FormController',
            'Backend.Behaviors.ListController'
        ];

        public $formConfig = 'form_config.yaml';

        public $listConfig = 'list_config.yaml';

        public function __construct()
        {
            parent::__construct();

            BackendMenu::setContext('Lasso.Petitions', 'petition', 'signatures');
        }
    }