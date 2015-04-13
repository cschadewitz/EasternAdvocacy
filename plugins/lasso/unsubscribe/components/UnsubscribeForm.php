<?php namespace Lasso\Unsubscribe\Components;

use Cms\Classes\ComponentBase;

class UnsubscribeForm extends ComponentBase
{

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
    }

    public function onUnsubscribe()
    {
        $email = post('email');
        $zip = post('zip');

        if ( empty($email) || empty($zip))
            throw new \Exception(sprintf('Please enter your email address.'));
        if ( empty($zip) )
            throw new \Exception(sprintf('Please enter your zip code.'));

        $user = Subscribe::whereRaw('email = ? and zip = ?', array($email, $zip));

        if ($user->count() == 0) {
            // Throw some error page
            $this->success = false;
            $this->message = "The entered email or zip did not match our records.";
            return;
        }

        $user->delete();

        $this->success = true;
        $this->message = "You have been successfully unsubscribed from the mailing list.";
    }

}