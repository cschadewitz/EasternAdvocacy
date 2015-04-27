<?php
    namespace Lasso\Petitions\Components;

    use Cms\Classes\ComponentBase;
    use Lasso\Petitions\Controllers\Petitions;

    class ViewPost extends Post
    {
        public $petition;

        public function componentDetails()
        {
            return [
                'name' => 'Frontend Petition View',
                'description' => 'Provides a frontend view for petitions.'
            ];
        }

        public function defineProperties()
        {
            return [
                'slug' => [
                    'title' => 'SLUG',
                    'description' => 'SLUG of the petition being viewed.',
                    'default' => '{{ :slug }}',
                    'type' => 'string',
                ],
            ];
        }

        public function onRun()
        {
            $slug = $this->param('slug');
            $this->petition = \Lasso\Petitions\Models\Petitions::url($slug)->first();
            $this->page['petitionInfo'] = $this->petition;
            $this->addCss('/plugins/lasso/petitions/assets/css/style.css');
        }
    }