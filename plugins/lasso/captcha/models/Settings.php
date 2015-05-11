<?php namespace Lasso\Captcha\Models;

use Model;

/**
 * Settings Model
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'lasso_captcha_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

}