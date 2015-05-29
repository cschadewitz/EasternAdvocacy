<?php namespace Lasso\Actions;

use System\Classes\PluginBase;
use Backend;

/**
 * Actions Plugin Information File
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
            'name'        => 'Actions',
            'description' => 'Allows admins to create Mail Templates that users would use to send pre-generated emails to their representatives.',
            'author'      => 'Lasso - Samir Ouahhabi',
            'icon'        => 'icon-send'
        ];
    }

    public function registerComponents()
    {
        return [
            'Lasso\Actions\Components\TakeAction' => 'takeAction',
            'Lasso\Actions\Components\Actions' => 'Actions'
        ];
    }

    public function registerNavigation()
    {
        return [
            'actions' => [
                'label'       => 'Actions',
                'url'         => Backend::url('lasso/actions/action'),
                'icon'        => 'icon-send',
                'permissions' => ['lasso.actions.*'],
                'order'       => 500,

                'sideMenu' => [
                    'action' => [
                        'label'       => 'New Action',
                        'icon'        => 'icon-plus',
                        'url'         => Backend::url('lasso/actions/action/create'),
                        'permissions' => ['lasso.actions.*'],
                    ],
                    'taken' => [
                        'label'       => 'Actions records',
                        'icon'        => 'icon-book',
                        'url'         => Backend::url('lasso/actions/action/taken'),
                        'permissions' => ['lasso.actions.*'],
                    ]
                ]

            ]
        ];
    }
}
