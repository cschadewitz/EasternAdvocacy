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
    protected $fillable = ['firstName','lastName','politicalParty','image','phoneNumber','physicalAddress'];

    protected $visible = ['id','firstName','lastName','politicalParty','image','phoneNumber','physicalAddress'];
    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    //public $belongsToMany = ['id' => ['Lasso\ZipLookup\Models\ZipRecord']];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}