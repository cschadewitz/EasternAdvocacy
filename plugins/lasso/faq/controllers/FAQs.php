<?php namespace Lasso\FAQ\Controllers;

use BackendMenu;
use System\Classes\SettingsManager;
use Backend\Classes\Controller;
use Lasso\FAQ\Models\FAQ;

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
        SettingsManager::setContext('Lasso.Faq', 'notifications');
    }

    public function all()
    {
        $this->pageTitle = "Admin FAQ";
        $this->addCss('/plugins/lasso/faq/assets/bootstrap/css/bootstrap.min.css');
        $this->addCss('/plugins/lasso/faq/assets/font-awesome/css/font-awesome.min.css');
        $this->addCss('/plugins/lasso/faq/assets/faq/css/faq.css');
        $this->addJs('/plugins/lasso/faq/assets/bootstrap/js/bootstrap.min.js');
        $faqs = FAQ::with('questions')->get();
        return $this->makePartial('all', ['faqs' => $faqs]);
    }
}