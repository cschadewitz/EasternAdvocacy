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

        public function onDelete(){
            $petitions = post("petition");
            foreach($petitions as $pet){
                if($pet != "on"){
                    //delete petition's signatures
                    \Lasso\Petitions\Models\Signatures::DeleteUsers($pet);
                    //delete petition
                    \Lasso\Petitions\Models\Petitions::find($pet)->delete();
                }
            }
            \Flash::success('Petition(s) Successfully deleted.');
            return $this->listRefresh();
        }
    }