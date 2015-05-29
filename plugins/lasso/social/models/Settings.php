<?php namespace Lasso\Social\Models;

use Model;

/**
 * Settings Model
 */
class Settings extends Model
{

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'lasso_social_settings';

    public $settingsFields = 'fields.yaml';

    public $attachOne = [
        'default_image' => ['System\Models\File']];

}
