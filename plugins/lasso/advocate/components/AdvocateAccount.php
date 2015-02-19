<?php namespace Lasso\Advocate\Components;

use Cms\Classes\ComponentBase;
use RainLab\User\Components\Account;

class AdvocateAccount extends Account
{

    public function componentDetails()
    {
        return [
            'name'        => 'AdvocateAccount Component',
            'description' => 'No description provided yet...'
        ];
    }

    /*public function defineProperties()
    {
        return [];
    }*/

}