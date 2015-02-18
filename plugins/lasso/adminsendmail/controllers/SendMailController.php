<?php

namespace lasso\adminsendmail\controllers;

class SendMailController extends \Backend\Classes\Controller
{
	public function send()
	{
		Mail::queue('lasso.adminsendmail::mail.message', array('name' => 'Samir', function($message)
		{
		    $message->to('samirpaulouahhabi@gmail.com', 'Samir Ouahhabi');
		});
	}
}