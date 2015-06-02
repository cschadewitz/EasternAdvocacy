<?php namespace Lasso\FAQ\Models;

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
                $message = 'New unanswered question: ' . $this->question.'<br/>
                    Whenever possible, could you please provide an answer to our content admins.<br/>Thank you!';
                $params = ['msg' => $message, 'subject' => 'New FAQ'];
                foreach ($contacts as $contact) {
                    Mail::send('lasso.faq::mail.default', $params, function ($message) use ($contact) {
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