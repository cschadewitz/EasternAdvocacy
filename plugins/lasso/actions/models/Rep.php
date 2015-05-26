<?php namespace Lasso\Actions\Models;

use Model;

/**
 * Rep Model
 */
class Rep extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lasso_actions_reps';

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
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}