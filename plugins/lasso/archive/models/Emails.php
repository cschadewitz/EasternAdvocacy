<?php
/**
 * Created by PhpStorm.
 * User: schad_000
 * Date: 1/25/2015
 * Time: 10:51 PM
 */

namespace Lasso\Archive\Models;
use App;
use Str;
use Model;

class Emails extends Model {

    protected $table = 'emails';

    protected $fillable = ['id', 'subject', 'abstract', 'content', 'created_at', 'updated_at', 'views'];

    protected $visible =  ['id', 'subject', 'abstract', 'content', 'created_at', 'updated_at', 'views'];

    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function scopeListPosts($query, $options)
    {
        extract(array_merge([
            'page'       => 1,
            'postsPerPage'    => 10,
        ], $options));

        App::make('paginator')->setCurrentPage($page);

        return $query->paginate($postsPerPage);
    }

    public function setUrl($pageName, $controller)
    {
        $params = [
            'id'    => $this->id,
            'slug'  => $this->id,
            ];
        return $this->url = $controller->pageUrl($pageName, $params);
    }
}