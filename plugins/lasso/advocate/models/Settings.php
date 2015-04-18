<?php namespace Lasso\Advocate\Models;

use Model;
use RainLab\User\Models\Settings as RainSettings;

/**
 * Settings Model
 */
class Settings extends RainSettings
{
    const LOGIN_ROLE = 'role';

    public function getLoginAttributeOptions() {
        return array_merge(parent::getLoginAttributeOptions(), [
            self::LOGIN_ROLE => ['Role']
        ]);
    }

}