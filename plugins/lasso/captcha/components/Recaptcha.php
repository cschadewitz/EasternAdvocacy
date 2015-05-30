<?php namespace Lasso\Captcha\Components;

use Cms\Classes\ComponentBase;
use Lasso\Captcha\Models\Settings;

class Recaptcha extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'ReCaptcha',
            'description' => 'A Captcha component using Google\'s ReCaptcha software'
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

    public function isDisabled()
    {
        return Settings::get('recaptcha_disabled');
    }
}
