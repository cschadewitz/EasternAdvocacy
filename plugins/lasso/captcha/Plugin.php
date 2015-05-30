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
            $result = array(
                'success' => false,
                'data' => ''
            );

            $disabled = Settings::get('recaptcha_disabled');
            $siteKey = Settings::get('recaptcha_site_key');
            $secret = Settings::get('recaptcha_secret_key');

            if ($disabled) {
                $result['success'] = true;
                return $result;
            }

            if (is_null($siteKey) || $siteKey == '') {
                $result['error'] = 'Administrative error: Recaptcha site key not set';
                return $result;
            }

            if (is_null($secret) || $secret == '') {
                $result['error'] = 'Administrative error: Recaptcha secret key not set';
                return $result;
            }
            if (is_null($recaptchaResponse) || $recaptchaResponse == "") {
                $result['error'] = 'No Recaptcha Response Provided';
                return $result;
            }

            $curl = curl_init('https://www.google.com/recaptcha/api/siteverify');

            $postFields = array(
                'secret' => $secret,
                'response' => $recaptchaResponse
            );

            $options = array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($postFields, '', '&'),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
                CURLINFO_HEADER_OUT => false,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false
            );
            curl_setopt_array($curl, $options);

            $captchaResponse = curl_exec($curl);

            if ($captchaResponse == false) {
                $result['error'] = 'Error Reaching Recaptcha Site: ' . curl_error($curl);
                return $result;
            }

            curl_close($curl);

            $captchaResponse = json_decode($captchaResponse);

            if (json_last_error() != JSON_ERROR_NONE) {
                $result['error'] = "JSON Error: " . json_last_error_msg();
                return $result;
            }

            if (is_null($captchaResponse) || !(is_object($captchaResponse) || is_array($captchaResponse))) {
                $result['error'] = 'Response is not valid';
                return $result;
            }
            if ($captchaResponse->success == true) {
                $result['success'] = true;
                return $result;
            }

            $errorCodes = 'error-codes';

            $result['error'] = 'Captcha verification failed';
            $result['data'] = implode(', ', $captchaResponse->$errorCodes);

            return $result;
        });
    }

}
