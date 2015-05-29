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

    public function getDefault_imageThumb($size = 25, $default = null)
    {
        if (!$default) {
            $default = 'mm'; // Mystery man
        }

        if ($this->default_image) {
            return $this->default_image->getThumb($size, $size);
        }
        else {
            return 'https://en.gravatar.com/avatar/93b1dacdd24fa8e1f21e446f3f244d18';
        }
    }
}
