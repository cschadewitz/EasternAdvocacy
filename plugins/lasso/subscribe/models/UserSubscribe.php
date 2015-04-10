<?php namespace Lasso\Subscribe\Models;

use Model;

/**
 * UserSubscribe Model
 */
class UserSubscribe extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lasso_subscribe_user_subscribes';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['id', 'user_id'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['verificationDate'];

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

    public static function getModel($user)
    {
        if($user->usersubscribe)
            return $user->usersubscribe;
        $usersubscribe = new static;
        $usersubscribe->user = $user;
        $usersubscribe->user_id = $user->id;
        $usersubscribe->verificationDate = null;
        $usersubscribe->save();
        return $usersubscribe;
    }

}