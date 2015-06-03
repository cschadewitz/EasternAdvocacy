<?php namespace Lasso\Faq\Models;

use Model;
use System\Models\MailTemplate;

/**
 * Settings Model
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'lasso_faq_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';


    public function getNotificationTemplateOptions()
    {
        return [''=>'- Do not send a notification -'] + MailTemplate::orderBy('code')->lists('code', 'code');
    }
}