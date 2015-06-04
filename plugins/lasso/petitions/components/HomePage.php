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

class HomePage extends \Cms\Classes\ComponentBase
{
    public $petitions;

    function componentDetails()
    {
        return [
            'name' => 'Petition Homepage List View',
            'description' => 'Homepage list view for petitions',
        ];
    }

    public function defineProperties()
    {
        return [
            'petitionsOnHomePage' => [
                'title'             => 'Petitions On Home Page',
                'description'       => 'Number of petitions to display on home page.',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format',
                'default'           => '5',
            ],
            'noPetitionsMessage' => [
                'title'        => 'No Petitions Message',
                'description'  => 'Message to be displayed if no petitions are found',
                'type'         => 'string',
                'default'      => 'No petitions found'
            ],
        ];
    }

        function petitions()
        {
            $petitions = \Lasso\Petitions\Models\Petitions::Published()->orderBy('created_at', 'desc')->take($this->property('petitionsOnHomePage'))->get();
            $result = [];
            foreach($petitions as $petition){
                $store = [];
                foreach($petition as $code => $data){
                    $store[$code] = $data;
                }
                $result[$petition['title']] = $store;
            }
            return $result;
        }

    public function onRun()
    {
        $this->addJs('/plugins/lasso/petitions/assets/ajax.js');
        $this->prepareVars();
    }

    protected function prepareVars()
    {
        $this->noPostsMessage = $this->page['noPetitionsMessage'] = $this->property('noPetitionsMessage');
        $this->postPage = $this->page['petitionPage'] = $this->property('petitionPage');
        $this->petitions = $this->petitions();

    }
}