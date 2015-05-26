<?php

Route::get('/take-action/lookupreps/html/{zip}', function($zip)
{
    $takeAction = new Lasso\Actions\Components\TakeAction;

    return $takeAction->getRepsHtml($zip);
});

Route::get('/take-action/lookupreps/json/{zip}', function($zip)
{
    $takeAction = new Lasso\Actions\Components\TakeAction;

    return $takeAction->lookupReps($zip);
});