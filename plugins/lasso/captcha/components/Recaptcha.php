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
        Event::listen("lasso.captcha.recaptcha.verify", function($recaptchaResponse) {
            return verify($recaptchaResponse);
        });
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

    public function verify($recaptchaResponse)
    {
        $captchaRequest = new \HttpRequest('https://www.google.com/recaptcha/api/siteverify', HttpRequest::METH_POST);
        $captchaRequest->addPostFields(array('secret'=>secretKey(), 'response'=>$recaptchaResponse));

        try {
            $captchaResponse = json_decode($captchaRequest->send()->getBody());
            if ($captchaResponse->success == false)
                return false;

        } catch (HttpException $ex) {
            return false;
        }
    }

}
