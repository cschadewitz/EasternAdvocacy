<?php namespace Lasso\Unsubscribe\Components;

use Cms\Classes\ComponentBase;
use Lasso\Captcha\Components\Recaptcha;
use Event;

class UnsubscribeForm extends ComponentBase
{
    public $success = null;

    public $message = null;

    public function componentDetails()
    {
        return [
            'name'        => 'Unsubscribe Form',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onInit()
    {
    }

    public function onRun()
    {
        $this->addCss('/plugins/lasso/subscribe/assets/css/component-style.css');
        $this->addJs('https://www.google.com/recaptcha/api.js');
    }

    public function onUnsubscribe()
    {
        // Todo: Research AJAX Stuff for this: https://octobercms.com/docs/cms/ajax#components-ajax-handlers
        $email = post('email');
        $captcha = post('g-recaptcha-response');

        if (empty($email)) {
            $this->throwError('Error: Please enter your email address.');
            return;
        }
        if (empty($captcha)) {
            $this->throwError('Error: Please enter the Captcha');
            return;
        }

        $captchaResponse = Event::fire('lasso.captcha.recaptcha.verify', $captcha)[0];

        if ($captchaResponse['success'] == false) {
            $this->throwError('Error: ' . $captchaResponse['error'] . ' Data: ' . $captchaResponse['data']);
            return;
        }

        $unsubResponse = Event::fire('lasso.unsubscribe.unsubscribe', $email);

        if (!$unsubResponse) {
            $this->throwError("Error: The entered email is not subscribed to the mailing list.");
            return;
        }

        $this->success = true;
        $this->message = "You have been successfully unsubscribed from the mailing list.";
    }

    private function throwError($message = 'An error occurred')
    {
        $this->success = false;
        $this->message = $message;
    }

    public function flash($key)
    {
        if ($key == "success") {
            $result = $this->success;
            $this->success = null;
            return $result;
        }
        if ($key == "message") {
            $result = $this->message;
            $this->message = null;
            return $result;
        }
    }
}