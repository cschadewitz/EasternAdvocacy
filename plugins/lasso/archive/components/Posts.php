<?php namespace Lasso\Archive\Components;

use App;
use Request;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Lasso\Archive\Models\Emails;
class Posts extends ComponentBase
{
    /**
     * A collection of posts to display
     * @var Collection
     */
    public $posts;

    /**
     * Parameter to use for the page number
     * @var string
     */
    public $pageNumber;
    public $postsPerPage;
    public $postPage;

    public $noPostsMessage;

    public function componentDetails()
    {
        return [
            'name'        => 'Posts Component',
            'description' => 'Displays archived news posts'
        ];
    }

    public function defineProperties()
    {
        return [
            'pageNumber' => [
                'title'       => 'Page Number',
                'description' => 'Determines the page that the user is on',
                'type'        => 'string',
                'default'     => '{{ :pageNumber }}',
            ],
            'postsPerPage' => [
                'title'             => 'Posts Per Page',
                'description'       => 'Number of posts to display per page.',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the posts per page value',
                'default'           => '10',
            ],
            'noPostsMessage' => [
                'title'        => 'No Posts Message',
                'description'  => 'Message to be displayed if no posts are found',
                'type'         => 'string',
                'default'      => 'No news found'
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

    public function init()
    {
        //Will execute on component initialization and on AJAX events
    }
    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }
    public function onRun()
    {
        $this->assignVars();
        $this->getAssets();


        //Will not exeute for AJAX events
        #$postId = $this->property('pageNumber');
        #if(twig_test_empty($this->pageNumber))
        #    $this->pageNumber = '1';

        $this->posts = Emails::orderBy('created_at', 'desc')->listPosts([
            'page'          => $this->pageNumber,
            'postsPerPage'  => $this->property('postsPerPage')
        ]);

        $this->posts->each(function($post) {
            $post->setUrl($this->postPage, $this->controller);
        });

        if ($pageNumberParam = $this->paramName('pageNumber')) {


            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->posts->getLastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl(['pageNumber' => $lastPage]));
        }
    }

    public function getAssets()
    {
        $this->addCss('/plugins/lasso/archive/assets/css/archive.css');
    }

    public function assignVars()
    {
        $pn = $this->property('pageNumber');
        $this->noPostsMessage = $this->page['noPostsMessage'] = $this->property('noPostsMessage');
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
        $this->pageNumber = $this->page['pageNumber']  = $this->paramName('pageNumber', $pn);
        $this->postsPerPage = $this->page['postsPerPage'] = $this->property('postsPerPage');
    }



}