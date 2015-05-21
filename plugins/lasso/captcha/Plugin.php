<?php namespace Lasso\Captcha;

use System\Classes\PluginBase;
use Event;
use Lasso\Captcha\Models\Settings;

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
            $curl = curl_init('https://www.google.com/recaptcha/api/siteverify');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'secret' => Settings::get('recaptcha_secret_key'),
                'response' => $recaptchaResponse
            ));

            $captchaResponse = curl_exec($curl);

            curl_close($curl);

            if (is_null($captchaResponse) || !is_object($captchaResponse))
                return false;
            if ($captchaResponse->success == true)
                return true;

            return false;
        });
    }

}
