<?php namespace Lasso\Faq\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Lasso\FAQ\Models\Question;

/**
 * Questions Back-end Controller
 */
class Questions extends Controller
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

        BackendMenu::setContext('Lasso.FAQ', 'faq', 'questions');
    }

    public function unanswered()
    {
        BackendMenu::setContext('Lasso.FAQ', 'faq', 'answers');
        $this->pageTitle = "Answer questions";
        $this->addCss('/plugins/lasso/faq/assets/bootstrap/css/bootstrap.min.css');
        $questions = Question::with('faq')->where('answered', '=', false)->get();
        return $this->makePartial('unanswered', ['questions' => $questions]);
    }
}