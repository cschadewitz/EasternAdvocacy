<?php namespace Lasso\Unsubscribe\Components;

use Cms\Classes\ComponentBase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Lasso\Captcha\Components\Recaptcha;

class UnsubscribeForm extends ComponentBase
{
    private $captchaSecret = '6LeBkgUTAAAAAPumAWlBZUCdoOGNdY8IugX6B3Ia';

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

    public function onRun()
    {
        $this->addCss('/plugins/lasso/subscribe/assets/css/component-style.css');
        $this->addJs('https://www.google.com/recaptcha/api.js');
    }

    public function onUnsubscribe()
    {
        $email = post('email');
        $zip = post('zip');
        $captcha = post('g-recaptcha-response');

        if ( empty($email) || empty($zip))
            throw new \Exception(sprintf('Please enter your email address.'));
        if ( empty($zip) )
            throw new \Exception(sprintf('Please enter your zip code.'));
        if ( empty($captcha))
            throw new \Exception(sprintf('Please enter the Captcha'));

        // Change the next bit to use the verify function in the Recaptcha plugin

        $captchaRequest = new \HttpRequest('https://www.google.com/recaptcha/api/siteverify', HttpRequest::METH_POST);
        $captchaRequest->addPostFields(array('secret'=>$this->captchaSecret, 'response'=>$captcha));

        try {
            $captchaResponse = json_decode($captchaRequest->send()->getBody());
            if ($captchaResponse->success == false)
                throw new \Exception(sprintf('Incorrect Captcha Response'));

        } catch (HttpException $ex) {
            throw new \Exception(sprintf('Could not verify Captcha'));
        }

        $user = Subscribe::whereRaw('email = ? and zip = ?', array($email, $zip));

        if ($user->count() == 0) {
            // Throw some error page
            throw new \Exception(sprintf("The entered email or zip did not match our records."));
        }

        $user->delete();

        $this->success = true;
        $this->message = "You have been successfully unsubscribed from the mailing list.";
    }

}