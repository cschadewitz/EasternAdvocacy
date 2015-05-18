<?php namespace Lasso\Captcha\Components;

use Cms\Classes\ComponentBase;
use Lasso\Captcha\Models\Settings;

class Recaptcha extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'recaptcha Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onInit()
    {
    }

    public function onRun()
    {
    }


    public function siteKey()
    {
        return Settings::get('recaptcha_site_key');
    }

    private function secretKey()
    {
        return Settings::get('recaptcha_secret_key');
    }



}
