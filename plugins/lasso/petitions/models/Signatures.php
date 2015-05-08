<?php
/**
 * Created by PhpStorm.
 * User: Wolf
 * Date: 2/28/2015
 * Time: 5:11 PM
 */

namespace Lasso\Petitions\Models;

use Model;

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

    public function SignatureValid($email, $pid)
    {
        $test = Signatures::EmailPid($email, $pid);
        if($test->count() > 0)
        {
            return 0;
        }
        return 1;
    }
}