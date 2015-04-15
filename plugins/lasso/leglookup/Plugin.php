<?php namespace Lasso\LegLookup;

use System\Classes\PluginBase;

/**
 * LegLookup Plugin Information File
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
            'name'        => 'LegLookup',
            'description' => 'Refactored Legislative Lookup plugin',
            'author'      => 'Team Lasso - Dan',
            'icon'        => 'icon-leaf'
        ];
    }
    /**
     * @return array
     */
    public function registerComponents()
    {
        return [
            '\Lasso\LegLookup\Components\Lookup' => 'lookup'
        ];
    }
}
