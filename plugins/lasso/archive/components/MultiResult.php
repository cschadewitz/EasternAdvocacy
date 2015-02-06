<?php namespace Lasso\Archive\Components;

use Cms\Classes\ComponentBase;

class MultiResult extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'MultiResult Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'pageNumber' => [
                'title'         => 'Page Number',
                'description'   => 'The page number',
                'type'          => 'string',
                'default'       => '{{ :page }}',
            ]
        ];
    }

}