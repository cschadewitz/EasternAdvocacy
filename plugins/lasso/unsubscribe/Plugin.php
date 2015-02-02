<?php
namespace Lasso\Unsubscribe;

class Plugin extends \System\Classes\PluginBase
{
    public $require = ['Lasso.Subscriber'];

    public function pluginDetails()
    {
        return [
            'name' => 'Unsubscribe Plugin',
            'description' => 'Allows users to unsubscribe from emails.',
            'author' => 'Zach Lesperance',
            'icon' => 'icon-times'
        ];
    }

    public function registerComponents()
    {
        return [

        ];
    }
}