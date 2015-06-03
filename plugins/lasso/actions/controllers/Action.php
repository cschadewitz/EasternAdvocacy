<?php namespace Lasso\Actions\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Lasso\Actions\Models\Settings;

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

    public function taken($arg='recent')
    {
        $this->pageTitle = 'Action Records';
        BackendMenu::setContext('Lasso.Actions', 'actions', 'taken');
        $this->addCss('/plugins/lasso/actions/assets/bootstrap/css/bootstrap.min.css');
        $this->addJs('/plugins/lasso/actions/assets/js/taken.js');
        $partial = 'taken';

        if($arg=="recent")
            $actions = $this->showRecentRecords();
        elseif($arg=="all")
            $actions = $this->showAllRecords();
        else {
            $partial = 'taken_specific';
            $actions = $this->showSpecificRecord($arg);
        }

        $select = \Lasso\Actions\Models\Action::all();
        return $this->makePartial($partial, ['actions' => $actions, 'select' => $select]);
    }

    private function showAllRecords()
    {
        return \Lasso\Actions\Models\Action::with('taken')->get();
    }

    private function showRecentRecords()
    {
        return \Lasso\Actions\Models\Action::with('taken')->take(Settings::get('numrecentrecords'))->orderBy('is_active', 'desc')->orderBy('id', 'desc')->get();
    }

    private function showSpecificRecord($arg)
    {
        return \Lasso\Actions\Models\Action::with('taken')->find($arg);
    }
}