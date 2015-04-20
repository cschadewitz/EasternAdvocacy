<?php namespace Lasso\AutoGenMail\Models;

use Model;

/**
 * AGMail Model
 */
class AGMail extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lasso_autogenmail_a_g_mails';

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