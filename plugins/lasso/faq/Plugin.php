<?php namespace Lasso\FAQ;

use System\Classes\PluginBase;
use Backend;

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
            'icon'        => 'icon-question'
        ];
    }

    public function registerNavigation()
    {
        return [
            'faq' => [
                'label'       => 'FAQs',
                'url'         => Backend::url('lasso/faq/faqs/all'),
                'icon'        => 'icon-question',
                'permissions' => ['lasso.faq.*'],
                'order'       => 500,

                'sideMenu' => [
                    'faqs' => [
                        'label' => 'New FAQ category',
                        'icon' => 'icon-plus',
                        'url' => Backend::url('lasso/faq/faqs/create'),
                        'permissions' => ['lasso.faq.add_categories'],
                    ],
                    'questions' => [
                        'label' => 'New Question',
                        'icon' => 'icon-question',
                        'url' => Backend::url('lasso/faq/questions/create'),
                        'permissions' => ['lasso.faq.ask_questions'],
                    ],
                    'answers' => [
                        'label' => 'Answer Questions',
                        'icon' => 'icon-magic',
                        'url' => Backend::url('lasso/faq/questions/unanswered'),
                        'permissions' => ['lasso.faq.answer_questions'],
                    ],
                ],
            ],

        ];
    }
}
