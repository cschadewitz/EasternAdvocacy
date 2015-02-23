<?php namespace Lasso\ZipLookup;

use System\Classes\PluginBase;

/**
 * ZipLookup Plugin Information File
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
            'name'        => 'ZipLookup',
            'description' => 'Method to lookup legislator by zip code',
            'author'      => 'Team Lasso - Dan Aldous',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * @return array
     */
	public function registerComponents()
	{
		return [
			'\Lasso\ZipLookup\Components\Zip' => 'zip'
		];
	}
}
