<?php
    use Lasso\Subscribe\Controllers\VerifyController;

    Route::get('subscribe/verify/{uuid}', '\Lasso\Subscribe\Controllers\VerifyController@commit');