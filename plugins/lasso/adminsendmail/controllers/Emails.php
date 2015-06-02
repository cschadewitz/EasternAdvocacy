<?php namespace Lasso\Adminsendmail\Controllers;

use App;
use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use Lasso\Adminsendmail\Models\Email;
use Lasso\Subscribe\Models\Subscribe;
use Lasso\Subscribe\Models\UserExtension;
use Lasso\Adminsendmail\ReportWidgets\TopViewed;
use Mail;
use Redirect;
use System\Models\File;

/**
 * Emails Back-end Controller
 */
class Emails extends Controller {
	public $implement = [
		'Backend.Behaviors.FormController',
		'Backend.Behaviors.ListController',
	];

	public $formConfig = 'config_form.yaml';
	public $listConfig = 'config_list.yaml';

	public function __construct() {
		parent::__construct();

		$this->assignVars();

        $this->initWidgets();

        $this->injectAssets();

        BackendMenu::setContext('Lasso.Adminsendmail', 'email', 'emails');
	}

    private function initWidgets()
    {
        //top viewed widget
        $topViewedWidget = new TopViewed($this);
        $topViewedWidget->alias = 'topViewed';
        $topViewedWidget->bindToController();
    }

    private function injectAssets()
    {
        $this->addCss('/plugins/lasso/adminsendmail/assets/css/box.css');
        $this->addCss('/plugins/lasso/adminsendmail/assets/css/fileupload.css');
    }

	public function assignVars() {
		$this->popularPost = Email::orderBy('views', 'desc')->take(1)->get();
        $this->vars['postsTotal'] = Email::count();
        $this->vars['popularPost'] = $this->popularPost;
	}

	public function send($id = null) {
		$this->pageTitle = 'Send Email';
        $subs = Subscribe::verified()->get();
        $users = UserExtension::subscribers()->get();

		$email = Email::with('attachments')->find($id);

        if($this->canSend($email)) {
            $this->sendEmail($email);

            return $this->makePartial('send', [
                'sent' => true,
                'subs' => $subs,
                'users' => $users]);
        }
        else {
            return $this->makePartial('send', [
                'sent' => false,
                'error' => $this->getSendError($email),
                'subs' => $subs,
                'users' => $users
            ]);
        }
	}

    private function sendEmail($email)
    {
        $params = ['msg' => $email->content, 'subject' => $email->subject, 'unsubscribeUrl' => ''];

        $this->emailSubs($params, $email);

        $this->emailUsers($params, $email);

        $email->sent = true;
        $email->save();
    }

    private function emailSubs($params, $email)
    {
        $subs = Subscribe::verified()->get();

        foreach ($subs as $subscriber) {
            $params['unsubscribeUrl'] = '/unsubscribe/' . $subscriber->email . '/' . $subscriber->uuid;
            Mail::send('lasso.adminsendmail::mail.default', $params, function ($message) use ($subscriber, $email) {
                $message->to($subscriber->email, $subscriber->name);
                foreach ($email->attachments as $attachment) {
                    $message->attach(App::basePath() . $attachment->getPath());
                }
            });
        }
    }

    private function emailUsers($params, $email)
    {
        $users = UserExtension::subscribers()->get();

        foreach ($users as $user) {
            $params['unsubscribeUrl'] = '/user/account/';
            Mail::send('lasso.adminsendmail::mail.default', $params, function ($message) use ($user, $email) {
                $message->to($user->user->email, $user->user->name);
                foreach ($email->attachments as $attachment) {
                    $message->attach(App::basePath() . $attachment->getPath());
                }
            });
        }
    }

    private function getSendError($email)
    {
        if (is_null($email))
            return 'Invalid post id';

        if ($email->sent)
            return 'This email has already been sent';
    }

    private function canSend($email)
    {
        return !is_null($email) && !($email->sent);
    }
}
