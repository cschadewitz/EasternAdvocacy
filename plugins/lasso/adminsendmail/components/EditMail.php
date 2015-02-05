<?php namespace Lasso\AdminSendMail\Components;

use Cms\Classes\ComponentBase;

class EditMail extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Mail Editor',
            'description' => 'Offers the interface to send emails to subscribers.'
        ];
    }

    public function onRun()
	{
        $this->addJs('/themes/advocacy/assets/javascript/adminsendmail.js');
        $this->page['testVar'] = "Let's see...";
	}

    public function onUpdate()
    {
        $this->page['testVar'] = "we received data!";
    }
}