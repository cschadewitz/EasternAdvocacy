<?php
namespace Lasso\adminSendMail\Models;

use Model;

class Email extends Model 
{
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
    public $attachMany = [
        'attachments' => ['System\Models\File']
    ];
}