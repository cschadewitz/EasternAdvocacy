<?php namespace Lasso\Captcha\Components;

use Cms\Classes\ComponentBase;

class Recaptcha extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'recaptcha Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'sitekey' => [
                'title'             => 'Site Key',
                'description'       => 'Public key from recaptcha',
                'type'              => 'string',
                'required'          => true,
            ],
            'secretkey' => [
                'title'             => 'Secret Key',
                'description'       => 'Secret key from recaptcha',
                'type'              => 'string',
                'required'          => true,
            ]
        ];
    }

    public function registerComponents()
    {
        return [
            'Lasso\Captcha\Components\Recaptcha' => 'recaptcha'
        ];
    }

    public function onRun()
    {
    }

    public function siteKey()
    {
        return $this->property('sitekey');
    }

    public function secretKey()
    {
        return $this->property('secretkey');
    }

    public function verify($recaptchaResponse)
    {
        $captchaRequest = new \HttpRequest('https://www.google.com/recaptcha/api/siteverify', HttpRequest::METH_POST);
        $captchaRequest->addPostFields(array('secret'=>$this->property('secretkey'), 'response'=>$recaptchaResponse));

        try {
            $captchaResponse = json_decode($captchaRequest->send()->getBody());
            if ($captchaResponse->success == false)
                return false;

        } catch (HttpException $ex) {
            return false;
        }
    }

}