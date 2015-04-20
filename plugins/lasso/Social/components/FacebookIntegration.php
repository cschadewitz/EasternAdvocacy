<?php namespace Lasso\Social\Components;

use Cms\Classes\ComponentBase;
use Lasso\Social\Models\FacebookSettings as Settings;

class FacebookInegration extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'Facebook Integration',
            'description' => 'Required for all other Facebook social components'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->fbAppId = Settings::get('fb_app_id');
        $this->fbUrl = Settings::get('fb_url');
    }

}