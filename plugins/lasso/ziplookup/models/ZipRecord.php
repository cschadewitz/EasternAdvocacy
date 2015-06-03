<?php namespace Lasso\ZipLookup\Models;

use Model;

/**
 * ZipRecord Model
 */
class ZipRecord extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'lasso_ziplookup_zip_records';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['representative_id', 'zip'];
    protected $visible = ['representative_id', 'zip'];
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