<?php namespace Lasso\Adminsendmail\Controllers;

use BackendMenu;
use Request;
use Backend\Classes\Controller;
use Lasso\Adminsendmail\Models\Email;
use ApplicationException;
use DB;

/**
 * Emails Back-end Controller
 */
class Emails extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Lasso.Adminsendmail', 'adminsendmail', 'emails');
    }

    public function onSend()
    {
        return Request::all(); 
    }

    public function send($id = null)
    {
        $var;
        $email = Email::with('attachments')->find($id);
        /*if (Request::input('someVar') != 'someValue')
        throw new ApplicationException('Invalid value');*/

        return $this->makePartial('send', [
                'email' => $email
            ]);
    }
}