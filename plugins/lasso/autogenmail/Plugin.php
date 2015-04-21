<?php namespace Lasso\AutoGenMail;

use System\Classes\PluginBase;
use Backend;

/**
 * AutoGenMail Plugin Information File
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
            'name'        => 'AutoGenMail',
            'description' => 'Allows admins to create Mail Templates that users would use to send pre-generated emails to their representatives.',
            'author'      => 'Lasso - Samir Ouahhabi',
            'icon'        => 'icon-envelope'
        ];
    }

    public function registerNavigation()
    {
        return [
            'autogenmail' => [
                'label'       => 'Auto-Gen.Mail',
                'url'         => Backend::url('lasso/autogenmail/agmail'),
                'icon'        => 'icon-envelope',
                'permissions' => ['lasso.autogenmail.*'],
                'order'       => 500,

                'sideMenu' => [
                    'agmail' => [
                        'label'       => 'Auto-Gen.Mail',
                        'icon'        => 'icon-copy',
                        'url'         => Backend::url('lasso/autogenmail/agmail'),
                        'permissions' => ['lasso.autogenmail.*'],
                    ]
                ]

            ]
        ];
    }
}
