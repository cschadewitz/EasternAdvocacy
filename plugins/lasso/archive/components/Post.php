<?php namespace Lasso\Archive\Components;

use App;
use Request;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Lasso\Archive\Models\Emails;

class Post extends ComponentBase
{
    public $post;

    public $archivePage;

    public function componentDetails()
    {
        return [
            'name'        => 'Post Component',
            'description' => 'Provides ability to view individual archived emails.'
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
            ]
        ];
    }
    public function getArchivePageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->assignVars();
        $this->getAssets();


        $postId = $this->property('postId');
        if($this->property('postId') == "")
            return Redirect::route($this->archivePage);

        $this->post = Emails::where('id', '=', intval($postId))->first();
        $this->post->views = $this->post->views + 1;
        $this->post->save();

    }

    public function assignVars()
    {
        $this->archivePage = $this->page['archivePage'] = $this->property('archivePage');
    }

    public function getAssets()
    {
        $this->addCss('/plugins/lasso/archive/assets/css/archive.css');
    }

}