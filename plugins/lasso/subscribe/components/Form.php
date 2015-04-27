<?php
namespace Lasso\Subscribe\Components;

use Cms\Classes\ComponentBase;
use Mail;

class Form extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Subscription Form',
            'description' => 'Display the subscription form'
        ];
    }

    public function onSubscribe()
    {
        $name = post('name');
        $email = post('email');
        $zip = post('zip');
        $type = post('type');

        if ( empty($name) )
            throw new \Exception(sprintf('Please enter your name.'));
        if ( empty($email) )
            throw new \Exception(sprintf('Please enter your email address.'));
        if ( empty($zip) )
            throw new \Exception(sprintf('Please enter your zip code.'));
        if ( empty($type) )
            throw new \Exception(sprintf('Please select your affiliation.'));
        if((new \lasso\subscribe\models\Subscribe)->validEmail($email) == 0){
            throw new \Exception(sprintf('Email is not valid.'));
        }
        if((new\lasso\subscribe\models\Subscribe)->uniqueEmail($email) == 0){
            return [
                'error' => 'nonUniqueEmail',
            ];
        }
        else{
            $subscription = new \lasso\subscribe\models\Subscribe;
            $subscription->uuid = $uuid = $subscription->generateUUID();
            $subscription->name = $name;
            $subscription->email = $email;
            $subscription->zip = $zip;
            $subscription->type = $subscription->type2int($type);
            $subscription->save();
            $params = ['name' => $name, 'email' => $email, 'uuid' => $uuid];


            Mail::sendTo([$email => $name], 'lasso.subscribe::mail.verify', $params);
        }


    }

    public function onRun()
    {
        $this->addCss('/plugins/lasso/subscribe/assets/css/component-style.css');
        $this->addJs('/plugins/lasso/subscribe/assets/js/script.js');
    }
}