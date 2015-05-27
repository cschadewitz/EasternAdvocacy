<?php namespace Lasso\Subscribe\Models;

use Lasso\Subscribe\Controllers\Subscribe as Subscribers;
use Model;
use RainLab\User\Models\User;
use Lasso\Subscribe\Models\Subscribe as Subscribe;

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
    public $belongsTo = [ 'user' => ['RainLab\User\Models\User', 'key' => 'user_id'] ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function scopeSubscribers($query, $inverse = false)
    {
        $temp = $query->wherehas('user', function($q)
        {
            $q->where('is_activated', '=', 1);
        });
        if(!$inverse)
            return $temp->where('verificationDate', '!=', 'NULL');
        else
            return $temp->where('verificationDate', '=', 'NULL');
    }
    public static function newModel($verificationDate = null, $affiliation = null )
    {
        $userextension = new static;
        if($verificationDate)
        {
            $userextension->verificationDate = $verificationDate;
        }
        if($affiliation)
        {
            $userextension->affiliation = Subscribe::type2Int($affiliation);
        }
        return $userextension;
    }

    public static function getModel($user)
    {
        if($user->extension)
            return $user->extension;
        $userextension = new static;
        $userextension->user = $user;
        $userextension->user_id = $user->id;
        $userextension->verificationDate = null;
        //$userextension->affiliation = "other";
        $userextension->save();
        $user->extension = $userextension;
        //$user->push();
        return $userextension;
    }
}