<?php

namespace Lasso\Petitions\Updates;

use Seeder;
use Lasso\Petitions\Models\Petitions;
use Faker;

class SeedPetitionsTable extends Seeder{
    public function run(){
        $faker = Faker\Factory::Create();

        for($i = 0; $i < 100; $i++){
            $petition = Petitions::create(
                array(
                    'title' => $faker->sentence(5),
                    'summary' => $faker->text(50),
                    'body' => $faker->text(250),
                    'goal' => $faker->randomNumber(3),
                )
            );
        }
    }
}