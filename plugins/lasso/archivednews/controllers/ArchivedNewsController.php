<?php
/**
 * Created by PhpStorm.
 * User: schad_000
 * Date: 1/26/2015
 * Time: 12:17 AM
 */

namespace Lasso\ArchivedNews\Controllers;

use Backend\Classes\Controller;

class ArchivedNewsController extends Controller
{

    public function index()
    {
        return Email::orderBy('date', 'dec')->take(10)->get();
    }


}