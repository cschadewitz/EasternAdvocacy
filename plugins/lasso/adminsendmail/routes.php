<?php
use lasso\adminsendmail\controllers\SendMailController;

Route::get('sendmail/send', array('uses' => 'SendMailController@send'));