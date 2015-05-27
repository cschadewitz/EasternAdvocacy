<?php
namespace Lasso\AdminSendMail\Models;

use Backend\Helpers\Backend;
use Model;
use Backend\Facades\BackendAuth;

class Email extends Model {
	protected $table = 'lasso_adminsendmail_emails';

	protected $fillable = ['id', 'subject', 'abstract', 'content'];

	protected $visible = ['id', 'subject', 'abstract', 'content', 'created_at', 'updated_at', 'views'];

	protected $guarded = ['author_id'];
	public $hasOne = [];
	public $hasMany = [];
	public $belongsTo = ['author' => 'October\Rain\Auth\Models\User', 'key' => 'author_id'];
	public $belongsToMany = [];
	public $morphTo = [];
	public $morphOne = [];
	public $morphMany = [];
	public $attachOne = [];
	public $attachMany = [
		'attachments' => ['System\Models\File'],
	];

	use \October\Rain\Database\Traits\Validation;

	public $rules = [
		'subject' => 'required|between:4,77',
		'abstract' => 'required',
		'content' => 'required',
	];

	public function scopeListPosts($query, $options) {
		extract(array_merge([
			'page' => 1,
			'postsPerPage' => 10,
		], $options));

		return $query->paginate($postsPerPage, $page);
	}

	public function getAuthor($id)
	{
		return BackendAuth::getUserById($id);
	}

	public function setUrl($pageName, $controller) {
		$params = [
			'id' => $this->id,
			'slug' => $this->id,
		];
		return $this->url = $controller->pageUrl($pageName, $params);
	}
}
