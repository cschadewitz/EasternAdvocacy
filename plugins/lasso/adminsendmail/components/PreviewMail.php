<?php namespace Lasso\AdminSendMail\Components;

use Cms\Classes\ComponentBase;

class PreviewMail extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Preview Mail',
            'description' => 'Preview an email before sending it.'
        ];
    }

    public function onRun()
	{
        $this->page['subject'] = post('subject');
        $this->page['message'] = post('message');
	}
}