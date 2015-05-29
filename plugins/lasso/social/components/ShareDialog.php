<?php namespace Lasso\Social\Components;

use App;
use Request;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Lasso\Social\Models\Settings as Settings;
use Lasso\AdminSendMail\Models\Email;

class ShareDialog extends ComponentBase
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
            'title' => [
                'title'       => 'Title',
                'description' => 'Title for the share dialog',
                'required'    => 'true',
                'type'        => 'string',
                'default'     => Settings::get('default_title')
            ],
            'description'   => [
                'title'       => 'Description',
                'description' => 'Action page basename',
                'required'    => 'true',
                'type'        => 'string',
                'default'     => Settings::get('default_description')
            ],
            'image' => [
                'title'       => 'Image',
                'description' => 'Petition page basename',
                'required'    => 'true',
                'type'        => 'string',
                'default'     => Settings::get('default_image').getPublicPath()
            ]
        ];
    }

    public function onRun()
    {
        $this->assignVars();
    }
    public function assignVars()
    {
        $this->fbAppId = Settings::get('fb_app_id');
        $this->fbUrl = Settings::get('fb_url');
    }



}