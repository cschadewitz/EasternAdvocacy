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
            'description' => 'No description provided yet...',
            'author'      => 'Lasso',
            'icon'        => 'icon-leaf'
        ];
    }

}
