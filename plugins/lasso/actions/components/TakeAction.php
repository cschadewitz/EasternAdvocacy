<?php namespace Lasso\Actions\Components;

use Cms\Classes\ComponentBase;
use Lasso\Actions\Models\Action;
use Lasso\ZipLookup\Components\Zip;

class TakeAction extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'TakeAction Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'actionId' => [
                'title'       => 'ID',
                'description' => 'ID of the email being viewed by the user',
                'default'     => '{{ :actionId }}',
                'type'        => 'string'
                ]];
    }

    public function onRun()
    {
        $zip = new Zip;
        $this->addCss('/plugins/lasso/actions/assets/css/frontend.css');
        $action = Action::with('template')->find($this->property('actionId'));
        $this->page['action'] = $action;
        $this->page['reps'] = $zip->info('99004');
    }

}