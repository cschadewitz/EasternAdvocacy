<?php namespace Lasso\Subscribe\Components;

use Cms\Classes\ComponentBase;

class UserExtended extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'UserExtended Component',
            'description' => 'Adds features to the user plugin'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

}