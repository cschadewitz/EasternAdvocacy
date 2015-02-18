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

        $subs = Subscriber::whereNotNull('verificationDate')->get();
        $this->page['subs'] = $subs;

        $email = new Email;
        $email->subject = post('subject');
        $email->content = post('message');
        $email->abstract = substr(strip_tags(post('message')), 0, 140);
        $email->save();

        $params = ['msg' => $email->content, 
                    'subject' => $email->subject];


        foreach($subs as $subscriber)
        {
            \Mail::sendTo($subscriber, 'lasso.adminsendmail::mail.blank', $params);
        }
        
	}
}