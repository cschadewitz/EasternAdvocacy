<?php namespace Lasso\Unsubscribe;

use System\Classes\PluginBase;

/**
 * Unsubscribe Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['Lasso.Subscribe'];

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
            'Lasso\Unsubscribe\Components\UnsubscribeHandler' => 'unsubscribeHandler'
        ];
    }

}
