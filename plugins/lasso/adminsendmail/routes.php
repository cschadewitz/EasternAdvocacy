<?php

Route::get('sendmail/new', function()
{
	return 'Sending a new email';
});

Route::post('sendmail/new', array('uses' => 'SendMailController@preview'));