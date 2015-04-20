<?php
    namespace Lasso\Petitions;

    use Backend\Facades\Backend;
    use System\Classes\PluginBase;

    class Plugin extends PluginBase
    {
        public function pluginDetails()
        {
            return [
                'name' => 'Petitions',
                'description' => 'Provides the interface for managing of petitions on EasternAdvocates.org.',
                'author' => 'Daniel Schultz',
                'icon' => 'icon-envelope'
            ];
        }

        public function registerPermissions()
        {
            return [
                'lasso.petitions.access_petitions' => ['label' => 'Manage petitions', 'tab' => 'Petitions'],
                'lasso.petitions.access_signatures' => ['label' => 'Manage signatures', 'tab' => 'Petitions'],
            ];
        }

        public function registerComponents()
        {
            return [
                'Lasso\Petitions\Components\Post' => 'petitionPost',
			'Lasso\Petitions\Components\Viewpost' => 'viewPost'
            ];
        }

        public function registerNavigation()
        {
            return [
                'petition' => [
                    'label' => 'Petitions',
                    'url' => Backend::url('lasso/petitions/petitions'),
                    'icon' => 'icon-check-square',
                    'permissions' => ['lasso.petitions.*'],
                    'order' => 500,

                    'sideMenu' => [
                        'petitions' => [
                            'label' => 'Petitions',
                            'url' => Backend::url('lasso/petitions/petitions'),
                            'icon' => 'icon-check-square',
                            'permissions' => ['lasso.petitions.access_petitions'],
                        ],
                        'signatures' => [
                            'label' => 'Signatures',
                            'url' => Backend::url('lasso/petitions/signatures'),
                            'icon' => 'icon-check-square',
                            'permissions' => ['lasso.petitions.access_signatures'],
                        ],
                        'createpetition' => [
                            'label' => 'Create Petition',
                            'url' => Backend::url('lasso/petitions/petitions/create'),
                            'icon' => 'icon-check-square',
                            'permissions' => ['lasso.petitions.access_petitions'],
                        ]
                    ]
                ]
            ];
        }
    }