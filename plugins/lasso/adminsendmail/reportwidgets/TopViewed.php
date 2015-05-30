<?php
/**
 * Created by PhpStorm.
 * User: schad_000
 * Date: 2/26/2015
 * Time: 2:15 AM
 */

namespace Lasso\Adminsendmail\ReportWidgets;

use App;
use Backend\Classes\ReportWidgetBase;
use Cms\Classes\Page;
use Lasso\Adminsendmail\Models\Email;

class TopViewed extends ReportWidgetBase {

    public $posts;
    public $postPage;
    public $numberOfPosts;

    public $defaultAlias = 'topViewed';

    public function widgetDetails()
    {
        return [
            'name'        => 'Top Viewed',
            'description' => 'Shows the top viewed articles.'
        ];
    }

    public function defineProperties()
    {
        return  [
            'numberOfPosts' => [
                'title'             => 'Number of Top Posts',
                'description'       => 'Number of top posts to display.',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the posts per page value',
                'default'           => '5',
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


    public function render()
    {
        $this->assignVars();



        return $this->makePartial('reportWidget');
    }

    public function assignVars()
    {
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
        $this->numberOfPosts = $this->page['numberOfPosts'] = $this->property('numberOfPosts');
        $this->vars['rows'] = $this->posts = Email::orderBy('views', 'desc')->take($this->numberOfPosts)->get();
        $this->vars['postPage'] = $this->postPage;
    }
}