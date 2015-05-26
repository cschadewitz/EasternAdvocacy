<?php namespace Lasso\FAQ\Models;

use Model;

/**
 * FAQ Model
 */
class Question extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lasso_faq_questions';

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
        'faq' => 'Lasso\FAQ\Models\FAQ'
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}