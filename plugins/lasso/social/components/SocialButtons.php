<?php namespace Lasso\Social\Components;

use Cms\Classes\ComponentBase;
use Lasso\Social\Models\Settings;

class SocialButtons extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'SocialButtons',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->settings = Settings::instance();
    }


}