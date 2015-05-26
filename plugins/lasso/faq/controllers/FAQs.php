<?php namespace Lasso\FAQ\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * F A Qs Back-end Controller
 */
class FAQs extends Controller
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

        BackendMenu::setContext('Lasso.FAQ', 'faq', 'faqs');
    }
}