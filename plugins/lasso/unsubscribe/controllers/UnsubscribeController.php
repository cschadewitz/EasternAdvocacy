<?php

namespace Lasso\Unsubscribe\Controllers;

class UnsubscribeController extends \Backend\Classes\Controller
{
    public function index()
    {

    }

    public function unsubscribe ($email, $uuid)
    {
        $user = Subscriber::whereRaw('email = ? and uuid = ?', array($email, $uuid))->take(1)->get();

        if (!$user) {
            // Throw some error page
        }

        $user->delete();
    }
}