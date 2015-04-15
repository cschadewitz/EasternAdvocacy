<?php namespace Lasso\LegislativeLookup;

use System\Classes\PluginBase;

/**
 * LegislativeLookup Plugin Information File
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
            'name'        => 'LegislativeLookup',
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
            '\Lasso\LegislativeLookup\Components\Lookup' => 'lookup'
        ];
    }
}
