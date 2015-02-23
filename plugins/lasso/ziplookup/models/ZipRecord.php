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
    protected $fillable = ['zip', 'representative_id'];
    protected $visible = ['zip', 'representative_id'];
    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    //public $hasMany = ['representative_id' => ['Lasso\ZipLookup\Models\Rep']];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    //public $Zip;
    //public $RepresentativeID;

    //public function existingRecord()
    //{
       // if();
    //}
    /*public function getRepID()
    {
        return $this->RepresentativeID;
    }*/
}