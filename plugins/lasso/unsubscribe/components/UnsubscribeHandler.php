<?php namespace Lasso\Unsubscribe\Components;

use Cms\Classes\ComponentBase;
use Lasso\Subscribe\Models\Subscribe;

class UnsubscribeHandler extends ComponentBase
{
    public $success;

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
        ];
    }

    public function onInit()
    {
    }

    public function onRun()
    {
        $email = $this->param('email');
        $uuid = $this->param('uuid');
        if ($email == null || $uuid == null) {
            $this->success = false;
            $this->message = "Invalid Argument(s): " . $email . " & " . $uuid;
            return;
        }

        $user = Subscribe::whereRaw('email = ? and uuid = ?', array($email, $uuid));

        if ($user->count() == 0) {
            $this->success = false;
            $this->message = "Email Not Found";
            return;
        }

        $user->delete();

        $this->success = true;
        $this->message = "You have been successfully unsubscribed from the mailing list.";
    }

}