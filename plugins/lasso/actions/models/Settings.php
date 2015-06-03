<?php namespace Lasso\Actions\Models;

use Model;

/**
 * Settings Model
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'lasso_actions_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

}