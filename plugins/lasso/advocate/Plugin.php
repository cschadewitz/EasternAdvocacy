<?php namespace Lasso\Advocate;

use System\Classes\PluginBase;

/**
 * Advocate Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['RainLab.User'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Advocate',
            'description' => 'No description provided yet...',
            'author'      => 'Lasso',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerComponents ()
    {
        return [
            'Lasso\Advocate\Components\AdvocateAccount' => 'advocateAccount'
        ];
    }

}
