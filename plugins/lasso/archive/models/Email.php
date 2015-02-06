<?php
/**
 * Created by PhpStorm.
 * User: schad_000
 * Date: 1/25/2015
 * Time: 10:51 PM
 */

namespace Lasso\Archive\Models;

use Model;

class Emails extends Model {

    protected $table = 'emails';
    protected $guarded = ['id'];

    protected $fillable = ['subject', 'abstract', 'content', 'createdOn', 'modifiedOn'];

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