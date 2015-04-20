<?php namespace Lasso\Social\Components;

use Cms\Classes\ComponentBase;
use Lasso\Social\Models\Settings as Settings;
use Lasso\AdminSendMail\Models\Email;

class FacebookShareDialog extends ComponentBase
{
    public $post = null;
    public $postPage = null;
    public $fbAppId = null;


    public function componentDetails()
    {
        return [
            'name'        => 'Facebook Share Dialog',
            'description' => 'This component should be added within the head of the layout used for individual post
                               pages. This will add content Facebook share dialog.'
        ];
    }

    public function defineProperties()
    {
        return [
            'postId' => [
                'title'       => 'ID',
                'description' => 'ID of the email being viewed by the user',
                'default'     => '{{ :postId }}',
                'type'        => 'string'
            ],
            'archivePage'   => [
                'title'       => 'Archive Page',
                'description' => 'Archive page basename',
                'type'        => 'dropdown',
                'default'     => 'archive',
                'group'       => 'Links',
            ],
            'postPage'      => [
                'title'     => 'Post Page',
                'description' => 'Post page basename',
                'type'        => 'dropdown',
                'default'     => 'archive',
                'group'       => 'Links',
            ]
        ];
    }

    public function getArchivePageOptions()
    {
        return getPageOptions();
    }
    public function getPostPageOptions()
    {
        return getPageOptions();
    }
    public function getPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {

        if($this->page->id != "article")
            return;

        $this->assignVars();


        $postId = $this->property('postId');
        if ($this->property('postId') == "")
            return Redirect::route($this->archivePage);

        $this->post = Email::where('id', '=', intval($postId))->first();
    }
    public function assignVars()
    {
        $this->fbAppId = Settings::get('fb_app_id');
        $this->fbUrl = Settings::get('fb_url');
        $this->archivePage = $this->page['archivePage'] = $this->property('archivePage');
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
    }



}