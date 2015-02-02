<?php

namespace Lasso\Unsubscribe\Controllers;

use GuzzleHttp\Message\Response;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Database\Attach\Resizer;

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
            return Redirect::action('error');
        }

        $user->delete();

        return Redirect::action('success');
    }

    public function success ()
    {
        return Response::json(array('message' => 'You have been unsubscribed successfully'));
    }

    public function error ()
    {
        return Response::json(array('message' => 'There was an error when attempting to unsubscribe you, or you were not found in our system.'));
    }
}