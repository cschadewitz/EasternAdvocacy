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

class Frontend extends \Cms\Classes\ComponentBase
{
    function componentDetails()
    {
        return [
            'name' => 'Petition Frontend',
            'description' => 'Front page view for petitions',
        ];
    }

    function petitions()
    {
        $petitions = \Lasso\Petitions\Models\Petitions::Active()->get();
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
}