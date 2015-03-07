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
    protected $table = 'signatures';

    protected $primaryKey = 'sid';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $belongsTo = [
        'petitions' => ['Lasso\Petitions\Models\Petitions'],
    ];
}