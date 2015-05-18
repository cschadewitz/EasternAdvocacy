<?php namespace Lasso\Captcha;

use System\Classes\PluginBase;
use Event;

/**
 * Captcha Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Captcha',
            'description' => 'Captcha Plugin (using ReCaptcha)',
            'author'      => 'Lasso',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'ReCaptcha Settings',
                'description' => 'Manage ReCaptcha Settings.',
                'category'    => 'Captcha',
                'icon'        => 'icon-cog',
                'class'       => 'Lasso\Captcha\Models\Settings',
                'order'       => 500,
                'keywords'    => 'captcha recaptcha'
            ]
        ];
    }

    public function registerComponents ()
    {
        return [
            'Lasso\Captcha\Components\Recaptcha' => 'recaptcha',
        ];
    }

    public function boot()
    {
        Event::listen("lasso.captcha.recaptcha.verify", function($recaptchaResponse) {
            return verify($recaptchaResponse);
        });
    }

    private function verify($recaptchaResponse)
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

        return true;
    }

}
