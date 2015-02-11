<?php namespace Lasso\AdminSendMail\Components;

use Cms\Classes\ComponentBase;
use Lasso\AdminSendMail\Models\Subscriber;
use Lasso\AdminSendMail\Models\Email;

class SendMail extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Send Mail',
            'description' => 'Send the email to specified recipients.'
        ];
    }

    public function onRun()
	{
        $this->page['subject'] = post('subject');
        $this->page['message'] = post('message');

        $subs = Subscriber::all();
        $this->page['subs'] = $subs;

        $email = new Email;
        $email->subject = post('subject');
        $email->content = post('message');
        $email->save();
	}
}