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
            'description' => 'This component should be added to the layout. The partial can be added anywhere and contains '
        ];
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title'       => 'Title',
                'description' => 'Title for the share dialog',
                'type'        => 'string',
                'default'     => Settings::get('default_title')
            ],
            'description'   => [
                'title'       => 'Description',
                'description' => 'Description for the share dialog',
                'type'        => 'string',
                'default'     => Settings::get('default_description')
            ],
            'image' => [
                'title'       => 'Image',
                'description' => 'Featured image for the share dialog',
                'type'        => 'string',
                'default'     => Settings::get('default_image')
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