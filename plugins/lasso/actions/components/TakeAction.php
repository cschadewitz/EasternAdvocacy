<?php namespace Lasso\Actions\Components;

use Cms\Classes\ComponentBase;

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
        $this->page['actionId'] = $this->property('actionId');
    }

}