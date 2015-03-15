<?php
namespace Lasso\AdminSendMail\Models;

use Model;
use App;

class Email extends Model 
{
    protected $table = 'lasso_adminsendmail_emails';

    protected $fillable = ['id', 'subject', 'abstract', 'content'];

    protected $visible =  ['id', 'subject', 'abstract', 'content', 'created_at', 'updated_at', 'views'];

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

    public function scopeListPosts($query, $options)
    {
        extract(array_merge([
            'page'       => 1,
            'postsPerPage'    => 10,
        ], $options));

        return $query->paginate($postsPerPage, $page);
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