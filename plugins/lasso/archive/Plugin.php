<?php namespace Lasso\Archive;
/**
 * Created by PhpStorm.
 * User: schad_000
 * Date: 1/25/2015
 * Time: 10:59 PM
 */
use Backend;
use System\Classes\PluginBase;

Class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Archive',
            'description' => 'Provides ability to view archived newsletters that have been emailed out.',
            'author'      => 'Team Lasso - Casey Schadewitz',
            'icon'        => 'icon-rss'
        ];
    }
    public function registerComponents()
    {
        return [
            '\Lasso\Archive\Components\Posts'       => 'posts',
            '\Lasso\Archive\Components\Post'        => 'post',
            '\Lasso\Archive\Components\HomeView'    => 'homeView'
        ];
    }

    public function registerReportWidgets()
    {
        return [
            '\Lasso\Archive\ReportWidgets\TopViewed'    =>  [
                'label'     =>      'TopViewed',
                'context'   =>      'dashboard',
            ]
        ];
    }

    public function registerNavigation()
    {
        return [
            'posts' => [
                'label' => 'Posts',
                'url' => Backend::url('lasso/archive/posts'),
                'icon' => 'icon-pencil',
                'order' => 500,

                'sideMenu' => [
                    'posts' => [
                        'label' => 'Archive',
                        'icon' => 'icon-archive',
                        'url' => Backend::url('lasso/archive/posts'),
                    ],

                    'newpost' => [
                        'label' => 'New Post',
                        'icon' => 'icon-newspaper-o',
                        'url' => Backend::url('lasso/archive/posts/create'),
                    ],
                ],
            ]
        ];
    }
}
