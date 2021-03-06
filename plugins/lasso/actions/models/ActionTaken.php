<?php namespace Lasso\Actions\Models;

use Model;

/**
 * ActionTaken Model
 */
class ActionTaken extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lasso_actions_action_takens';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'action' => 'Lasso\Actions\Models\Action'
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}