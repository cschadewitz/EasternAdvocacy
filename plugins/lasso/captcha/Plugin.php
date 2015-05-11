<?php namespace Lasso\Captcha;

use System\Classes\PluginBase;

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

}
