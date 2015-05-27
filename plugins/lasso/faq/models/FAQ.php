<?php namespace Lasso\FAQ\Models;

use Model;

/**
 * FAQ Model
 */
class FAQ extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lasso_faq_f_a_q_s';

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
    public $hasMany = [
        'questions' => ['Lasso\FAQ\Models\Question', 'key' => 'faq_id']
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}