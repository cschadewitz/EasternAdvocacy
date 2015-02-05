<?php namespace Lasso\Unsubscribe;

use System\Classes\PluginBase;

/**
 * Unsubscribe Plugin Information File
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
            'name'        => 'Unsubscribe',
            'description' => 'No description provided yet...',
            'author'      => 'Lasso',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerComponents ()
    {
        return [
            'Lasso\Unsubscribe\Components\UnsubscribeHandler' => 'UnsubscribeHandler'
        ];
    }

}
