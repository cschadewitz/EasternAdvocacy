<?php namespace Lasso\FAQ\Models;

use Backend;
use BackendAuth;
use Backend\Models\User as BackendUser;
use Mail;
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

    public function beforeSave()
    {
        if(!$this->answered) {
            $contacts = $this->getContacts();
            if (Settings::get('send_emails') && !is_null($contacts)) {
                $admin = BackendAuth::getUser()->first_name.' '.BackendAuth::getUser()->last_name;
                $question = $this->question;
                $category = $this->faq->title;
                $link = Backend::url('lasso/faq/questions/unanswered');
                $params = ['admin' => $admin,
                    'question' => $question,
                    'category' => $category,
                    'link' => $link];
                foreach ($contacts as $contact) {
                    Mail::send(Settings::get('notification_template'), $params, function ($message) use ($contact) {
                        $message->to($contact, 'Lasso');
                    });
                }
            }
        }
    }

    public function getContacts()
    {
        $contacts = array();

        for($i=1; $i<=5; $i++) {
            if (Settings::get('contact' . $i))
                array_push($contacts, Settings::get('contact' . $i));
        }

        return $contacts;
    }
}