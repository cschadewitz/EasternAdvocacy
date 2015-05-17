<?php namespace Lasso\Unsubscribe\Components;

use Cms\Classes\ComponentBase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Lasso\Captcha\Components\Recaptcha;

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

        if ( empty($email) )
            return throwError('Please enter your email address.');
        if ( empty($captcha))
            return throwError('Please enter the Captcha');

        $captchaResponse = Event::fire('lasso.captcha.recaptcha.verify', [$captcha]);

        if ($captchaResponse == false)
            return throwError('Could not verify Captcha');

        $unsubResponse = Event::fire('lasso.unsubscribe.unsubscribe', $email);

        if (!$unsubResponse)
            return throwError("The entered email or zip did not match our records.");

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

    public function isPostBack()
    {
        return !is_null($this->success);
    }

}