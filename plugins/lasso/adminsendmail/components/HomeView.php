<?php namespace Lasso\Adminsendmail\Components;

use App;
use Request;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Lasso\Adminsendmail\Models\Email;

class HomeView extends ComponentBase
{
    public $postPage;
    public $numberOfPosts;
    public $posts;

    public function componentDetails()
    {
        return [
            'name'        => 'HomeView Component',
            'description' => 'Component meant to display recent posts on the Home page'
        ];
    }

    public function defineProperties()
    {
        return [
            'numberOfPosts' => [
                'title'             => 'Number of Posts',
                'description'       => 'Number of recent posts to display.',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the posts per page value',
                'default'           => '4',
            ],
            'postPage' => [
                'title'       => 'Post Page',
                'description' => 'Post page basename',
                'type'        => 'dropdown',
                'default'     => 'article',
                'group'       => 'Links',
            ],
        ];
    }
    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->assignVars();
        $this->getAssets();

        $this->posts = Email::orderBy('created_at', 'desc')->take($this->numberOfPosts)->get();

        $this->posts->each(function($post) {
            $post->setUrl($this->postPage, $this->controller);
        });
    }

    public function assignVars()
    {
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
        $this->numberOfPosts = $this->page['numberOfPosts'] = $this->property('numberOfPosts');
    }

    public function getAssets()
    {
        $this->addCss('/plugins/lasso/archive/assets/css/archive.css');
    }

}