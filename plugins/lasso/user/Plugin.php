<?php namespace Lasso\User;

use System\Classes\PluginBase;

/**
 * User Plugin Information File
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
            'name'        => 'User',
            'description' => 'No description provided yet...',
            'author'      => 'Lasso',
            'icon'        => 'icon-leaf'
        ];
    }

}
