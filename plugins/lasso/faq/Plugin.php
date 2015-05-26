<?php namespace Lasso\FAQ;

use System\Classes\PluginBase;

/**
 * FAQ Plugin Information File
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
            'name'        => 'FAQ',
            'description' => 'No description provided yet...',
            'author'      => 'lasso',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerNavigation()
    {
        return [
            'actions' => [
                'label'       => 'FAQs',
                'url'         => Backend::url('lasso/faq/faqs'),
                'icon'        => 'icon-send',
                'permissions' => ['lasso.faq.*'],
                'order'       => 500,
            ]
        ];
    }
}
