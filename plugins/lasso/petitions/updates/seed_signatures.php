<?php
/**
 * Created by PhpStorm.
 * User: Wolf
 * Date: 2/28/2015
 * Time: 5:29 PM
 */

namespace Lasso\Petitions\Updates;

use Seeder;
use Lasso\Petitions\Models\Signatures;

class SeedSignatures extends Seeder
{
    public function run()
    {
        $signature = Signatures::create([
            'name' => 'John Doe',
            'pid' => 1,
            'email' => 'johndoe@example.net',
            'address' => '1234 1st Ave.',
            'city' => 'Cheney',
            'zip' => '99004',
        ]);
    }
}