<?php namespace Lasso\Unsubscribe\Components;

use Cms\Classes\ComponentBase;

class UnsubscribeHandler extends ComponentBase
{
    public $email;

    public $uuid;

    public $message;

    public function componentDetails()
    {
        return [
            'name'        => 'Unsubscribe Handler',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'email' => [
                'title'             => 'Email',
                'description'       => 'Subscriber\'s Email Address',
                'validationPattern' => '([a-zA-Z0-9!#\\$\\&\'\\+\\-=\\?\\^_{\\|}~\\.]+)@[a-zA-Z0-9\\-]+(?:\\.[a-zA-Z0-9\\-]+)*\\.[a-zA-Z]{2,}',
                'validationMessage' => 'Invalid Email Address',
                'required'          => true

            ],
            'uuid' => [
                'title'             => 'UUID',
                'description'       => 'Unique User Identifier',
                'required'          => true
            ]
        ];
    }

    public function onRun()
    {

    }

}