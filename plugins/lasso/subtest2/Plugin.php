<?php namespace Lasso\Subtest2;

use System\Classes\PluginBase;

/**
 * Subtest2 Plugin Information File
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
            'name'        => 'Subtest2',
            'description' => 'No description provided yet...',
            'author'      => 'Lasso',
            'icon'        => 'icon-leaf'
        ];
    }

}
?>

