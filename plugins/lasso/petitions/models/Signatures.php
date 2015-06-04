<?php
/**
 * Created by PhpStorm.
 * User: Wolf
 * Date: 2/28/2015
 * Time: 5:11 PM
 */

namespace Lasso\Petitions\Models;

use Model;
use Mail;

class Signatures extends Model
{
    protected $table = 'lasso_petitions_signatures';

    protected $primaryKey = 'sid';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $belongsTo = [
        'petitions' => ['Lasso\Petitions\Models\Petitions'],
    ];

    function scopePid($query, $pid){
        return $query->where('pid', '=', $pid);
    }

    function scopeEmail($query, $email)
    {
        return $query->where('email', '=', $email);
    }

    function scopeEmailPid($query, $email, $pid){
        return $query->where('email', '=', $email)->where('pid', '=', $pid);
    }

    public function scopeDeleteUsers($query, $pid){
        $query->where('pid', '=', $pid)->delete();
    }

    public function SignatureValid($email, $pid)
    {
        $test = Signatures::EmailPid($email, $pid);
        if($test->count() > 0)
        {
            return 0;
        }
        return 1;
    }

    public function scopeEmailSignatures($query, $pid)
    {
        $results = \Lasso\Petitions\Models\Signatures::Pid($pid)->get();
        $petition = \Lasso\Petitions\Models\Petitions::Pid($pid)->first();

        foreach ($results as $r) {
            $params = ['name' => $r->name, 'email' => $r->email, 'petitionName' => $petition->title, 'slug' => $petition->slug];
            Mail::sendTo([$r->email => $r->name], 'lasso.petitions::mail.petition_changed', $params);
        }
    }
}