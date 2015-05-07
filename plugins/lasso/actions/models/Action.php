<?php namespace Lasso\Actions\Models;

use Model;

/**
 * Action Model
 */
class Action extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lasso_actions_actions';

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
        'template' => ['System\Models\MailTemplate']
        ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'photo' => ['System\Models\File']];
    public $attachMany = [];

}