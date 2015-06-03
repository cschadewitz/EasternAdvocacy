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

    public function registerSettings()
    {
        return [
            'notifications' => [
                'label'       => 'FAQ Settings',
                'description' => 'Manage FAQ settings.',
                'category'    => 'FAQ',
                'icon'        => 'icon-cog',
                'class'       => 'Lasso\Faq\Models\Settings',
                'order'       => 500,
                'keywords'    => 'faq',
                'permissions' => ['lasso.faq.settings'],
            ]
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'lasso.faq::mail.default' => 'Default template used by the FAQ plugin to send out notifications to devs.'
        ];
    }

    public function registerPermissions()
    {
        return [
            'lasso.faq.read_faqs'       => ['tab' => 'FAQ', 'label' => 'Read the FAQs'],
            'lasso.faq.manage_categories'  => ['tab' => 'FAQ', 'label' => 'Manage FAQ categories'],
            'lasso.faq.ask_questions'  => ['tab' => 'FAQ', 'label' => 'Create new questions'],
            'lasso.faq.answer_questions'  => ['tab' => 'FAQ', 'label' => 'Answer questions'],
            'lasso.faq.manage_questions'  => ['tab' => 'FAQ', 'label' => 'Manage all the questions'],
            'lasso.faq.settings'  => ['tab' => 'FAQ', 'label' => 'FAQ Settings']
        ];
    }

    public function registerNavigation()
    {
        return [
            'faq' => [
                'label'       => 'FAQs',
                'url'         => Backend::url('lasso/faq/faqs/all'),
                'icon'        => 'icon-question',
                'permissions' => ['lasso.faq.read_faqs'],
                'order'       => 500,

                'sideMenu' => [
                    'faqs' => [
                        'label' => 'New FAQ category',
                        'icon' => 'icon-plus',
                        'url' => Backend::url('lasso/faq/faqs/create'),
                        'permissions' => ['lasso.faq.manage_categories'],
                    ],
                    'list_categories' => [
                        'label' => 'FAQ categories',
                        'icon' => 'icon-th-list',
                        'url' => Backend::url('lasso/faq/faqs/'),
                        'permissions' => ['lasso.faq.manage_categories'],
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
                    'list_questions' => [
                        'label' => 'List of questions',
                        'icon' => 'icon-list',
                        'url' => Backend::url('lasso/faq/questions/'),
                        'permissions' => ['lasso.faq.manage_questions'],
                    ],
                ],
            ],

        ];
    }
}
