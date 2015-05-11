<?php namespace Lasso\Actions\Components;

use Cms\Classes\ComponentBase;
use Lasso\Actions\Models\Action;
use Twig;

class Actions extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Actions Component',
            'description' => 'Displays active and non-active actions.'
        ];
    }

    public function defineProperties()
    {
        return [
            'noActionsMessage' => [
                'title'        => 'No Actions Message',
                'description'  => 'Message to be displayed if no actions are found',
                'type'         => 'string',
                'default'      => 'No actions found'
            ],
        ];
    }

    public function onRun() {

        $this->injectAssets();

        $this->assignVars();
    }


    public function injectAssets()
    {
        $this->addCss('/plugins/lasso/actions/assets/css/frontend.css');
        $this->addJs('/plugins/lasso/actions/assets/js/frontend.js');
    }

    public function assignVars()
    {
        $this->page['noActionsMessage'] = $this->property('noActionsMessage');
        $actions = Action::with('template', 'photo')->orderBy('is_active', 'desc')->orderBy('created_at', 'desc')->get();
        $this->page['actions'] = $actions;
    }

}