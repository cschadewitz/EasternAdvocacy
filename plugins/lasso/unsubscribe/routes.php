<?php
/**
 * Created by PhpStorm.
 * User: Zach
 * Date: 1/31/2015
 * Time: 3:28 PM
 */
Route::get('unsubscribe/{email}/{uuid}', array('uses' => 'UnsubscribeController@unsubscribe'));
Route::get('unsubscribe/success', array('uses' => 'UnsubscribeController@success'));


