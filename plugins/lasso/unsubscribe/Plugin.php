<?php namespace Lasso\Unsubscribe;

use System\Classes\PluginBase;
use Event;

/**
 * Unsubscribe Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['Lasso.Subscribe'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Unsubscribe',
            'description' => 'No description provided yet...',
            'author'      => 'Lasso',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerComponents ()
    {
        return [
            'Lasso\Unsubscribe\Components\UnsubscribeHandler' => 'unsubscribeHandler',
            'Lasso\Unsubscribe\Components\UnsubscribeForm' => 'unsubscribeForm'
        ];
    }

    public function boot()
    {
        Event::listen("lasso.unsubscribe.unsubscribe", function($email) {
            return unsubscribe($email);
        });
    }

    private function unsubscribe($email)
    {
        $user = Subscribe::whereRaw('email = ?', array($email));

        if ($user->count() == 0)
            return false;

        $user->delete();

        return true;
    }

}
