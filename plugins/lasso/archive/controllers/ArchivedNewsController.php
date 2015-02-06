<?php
/**
 * Created by PhpStorm.
 * User: schad_000
 * Date: 1/26/2015
 * Time: 12:17 AM
 */

namespace Lasso\Archive\Controllers;

use Lasso\Archive\Models\Emails;
use Backend\Classes\Controller;

class ArchiveController extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function index()
    {
        $this->vars['']
    }

    public function create()
    {

    }

    public function store()
    {

    }

    public function show($id)
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }


}