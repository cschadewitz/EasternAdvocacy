<?php namespace Lasso\AdminSendMail;

use System\Classes\PluginBase;

/**
 * AdminSendMail Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Admin Send Mail',
            'description' => 'A plugin that allows to send an email to subscribers.',
            'author'      => 'lasso',
            'icon'        => 'icon-envelope'
        ];
    }

    public function registerComponents()
    {
        return [
            'Lasso\AdminSendMail\components\EditMail' => 'EditMail',
            'Lasso\AdminSendMail\components\SendMail' => 'SendMail'
        ];
    }

}
