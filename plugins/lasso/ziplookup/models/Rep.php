<?php namespace Lasso\ZipLookup\Models;

use Model;

/**
 * Rep Model
 */
class Rep extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lasso_ziplookup_reps';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['firstName','lastName','title','politicalParty','emailAddress','phoneNumber','physicalAddress','expireDate'];

    protected $visible = ['id','firstName','lastName','title','politicalParty','emailAddress','phoneNumber','physicalAddress','expireDate'];
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