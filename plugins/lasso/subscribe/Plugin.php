<?php
    namespace Lasso\Subscribe;

    use System\Classes\PluginBase;

    class Plugin extends PluginBase
    {
        public function pluginDetails()
        {
            return [
                'name' => 'Subscribe Plugin',
                'description' => 'Provides user the ability to subscribe to the Eastern Advocacy mailing list.',
                'author' => 'Daniel Schultz',
                'icon' => 'icon-envelope'
            ];
        }

        public function registerComponents()
        {
            return [
                '\lasso\Subscribe\Components\Form' => 'SubForm'
            ];
        }
    }