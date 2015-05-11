<?php namespace Lasso\LegislativeLookup\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'lasso_legistlativelookup_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}