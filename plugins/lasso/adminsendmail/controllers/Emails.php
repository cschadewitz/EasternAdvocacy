<?php namespace Lasso\Adminsendmail\Controllers;

use App;
use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use Lasso\Adminsendmail\Models\Email;
use Lasso\Adminsendmail\Models\Subscriber;
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
		$this->vars['postsTotal'] = Email::count();
		$this->vars['popularPost'] = $this->popularPost;
		BackendMenu::setContext('Lasso.Adminsendmail', 'email', 'emails');
		$topViewedWidget = new TopViewed($this);
		$topViewedWidget->alias = 'topViewed';
		$topViewedWidget->bindToController();
		$this->addCss('/plugins/lasso/adminsendmail/assets/css/box.css');
		$this->addCss('/plugins/lasso/adminsendmail/assets/css/fileupload.css');
	}

	public function assignVars() {
		$this->popularPost = Email::orderBy('views', 'desc')->take(1)->get();
	}

	public function onUpdate() {
		return Redirect::to(Backend::url('lasso/adminsendmail/emails/preview/' . $id));
	}

	public function send($id = null) {
		$issent = true;
		$error = '';
		$email = Email::with('attachments')->find($id);

		if (is_null($email)) {
			$issent = false;
			$error = 'Invalid post id';
		}

		if ($email->sent == true) {
			$issent = false;
			$error = 'This email has already been sent';
		}

		//send email
		$subs = Subscriber::all();
		$params = ['msg' => $email->content, 'subject' => $email->subject];
		foreach ($subs as $subscriber) {
			$params['unsubscribeUrl'] = '/unsubscribe/' . $subscriber->email . '/' . $subscriber->uuid;
			Mail::send('lasso.adminsendmail::mail.default', $params, function ($message) use ($subscriber, $email) {
				$message->to($subscriber->email, $subscriber->name);
				foreach ($email->attachments as $attachment) {
					$message->attach(App::basePath() . $attachment->getPath());
				}
			});
		}

		$email->sent = true;
		$email->save();

		$this->pageTitle = 'Send Email';

		return $this->makePartial('send', [
			'email' => $email,
			'sent' => $issent,
			'subs' => $subs,
			'error' => $error,
		]);
	}

	public function download($id = null) {
		$file = File::find($id);

		if (is_null($file)) {
			return Redirect::to(Backend::url('lasso/adminsendmail/emails'));
		}

		echo $file->output();
	}
}
