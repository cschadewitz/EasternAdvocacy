<?php namespace Lasso\Actions\Components;

use Cms\Classes\ComponentBase;
use Lasso\Actions\Models\Action;

class Actions extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'Actions Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
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
        $actions = Action::with('template', 'photo')->get();
        $this->page['actions'] = $actions;
    }

}