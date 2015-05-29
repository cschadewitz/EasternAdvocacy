<?php namespace Lasso\Social;

use System\Classes\PluginBase;
use Lasso\Social\Models\Settings;

/**
 * FacebookIntegration Plugin Information File
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
            'name'        => 'Social',
            'description' => '',
            'author'      => 'Lasso',
            'icon'        => 'icon-rss-square'
        ];
    }

    public function registerSettings()
    {
        return [
            'social' => [
                'label'       => 'Social',
                'description' => 'Manage Social api keys & urls',
                'category'    => 'Social',
                'icon'        => 'icon-rss-square',
                'class'       => 'Lasso\Social\Models\Settings',
                'order'       => 500,
                'keywords'    => 'facebook'
            ]
        ];
    }

    public function registerComponents()
    {
        return [
            'Lasso\Social\Components\Integration' => 'integration',
            'Lasso\Social\Components\ShareDialog' => 'share_dialog',
            'Lasso\Social\Components\SocialButtons'       => 'social_buttons'
        ];
    }

}
