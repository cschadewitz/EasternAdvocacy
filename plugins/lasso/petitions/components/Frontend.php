<?php
/**
 * Created by PhpStorm.
 * User: Wolf
 * Date: 4/23/2015
 * Time: 1:57 PM
 */
namespace Lasso\Petitions\Components;
use Illuminate\Support\Facades\DB;
use Lasso\Petitions\Controllers\Petitions;
use Redirect;
use Cms\Classes\Page;

class Frontend extends \Cms\Classes\ComponentBase
{
    public $petitions;
    public $pageNumber;
    public $petitionsPerPage;
    public $petitionPage;

    function componentDetails()
    {
        return [
            'name' => 'Petition List View',
            'description' => 'Front page list view for petitions',
        ];
    }

    public function defineProperties()
    {
        return [
            'showPublished' =>[
                'title' => 'Show Published',
                'description' => 'Show only published petitions',
                'type' => 'checkbox',
                'default' => 'true',
            ],
            'pageNumber' => [
                'title'       => 'Page Number',
                'description' => 'Determines the page that the user is on',
                'type'        => 'string',
                'default'     => '{{ :pageNumber }}',
            ],
            'petitionsPerPage' => [
                'title'             => 'Petitions Per Page',
                'description'       => 'Number of petitions to display per page.',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the petitions per page value',
                'default'           => '10',
            ],
            'noPetitionsMessage' => [
                'title'        => 'No Petitions Message',
                'description'  => 'Message to be displayed if no petitions are found',
                'type'         => 'string',
                'default'      => 'No petitions found'
            ],
            'petitionPage' => [
                'title'       => 'Petition Page',
                'description' => 'Petition page basename',
                'type'        => 'dropdown',
                'default'     => 'petition',
                'group'       => 'Links',
            ],
        ];
    }

    public function getPetitionPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }
    public function onRun()
    {
        $this->addCss('/plugins/lasso/petitions/assets/css/style.css');
        $this->prepareVars();

        $this->petitions = $this->page['petitions'] = \Lasso\Petitions\Models\Petitions::Published()->orderBy('created_at', 'desc')->listPosts([
            'page'          => $this->property('pageNumber'),
            'petitionsPerPage'  => $this->property('petitionsPerPage'),
        ]);

        $this->petitions->each(function($petition) {
            $petition->setUrl($this->petitionPage, $this->controller);
        });

        if ($pageNumberParam = $this->paramName('pageNumber')) {


            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->petitions->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl(['pageNumber' => $lastPage]));
        }
    }

    protected function prepareVars()
    {
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->noPostsMessage = $this->page['noPetitionsMessage'] = $this->property('noPetitionsMessage');
        $this->postPage = $this->page['petitionPage'] = $this->property('petitionPage');
    }
}