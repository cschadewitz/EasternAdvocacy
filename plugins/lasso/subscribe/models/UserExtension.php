<?php namespace Lasso\Subscribe\Models;

use Model;

/**
 * UserExtension Model
 */
class UserExtension extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lasso_subscribe_user_extensions';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['id', 'user_id'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['verificationDate', 'type'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [ 'user' => ['RainLab\User\Models\User']];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function scopeSubscribers($query, $inverse = false)
    {
        $temp = $query->with('user')->whereHas('user', function($userQuery){
            $userQuery->where('users.isActivated', '=', 1);
        });
        if(!$inverse)
            return $temp->where('verificationDate', 'IS NOT', 'NULL');
        else
            return $temp->where('verificationDate', 'IS', 'NULL');
    }

    public static function getModel($user)
    {
        if($user->userextension)
            return $user->userextension;
        $userextension = new static;
        $userextension->user = $user;
        $userextension->user_id = $user->id;
        $userextension->verificationDate = null;
        $userextension->affiliation = "other";
        $userextension->save();
        $user->userextension = $userextension;
        return $userextension;
    }
}