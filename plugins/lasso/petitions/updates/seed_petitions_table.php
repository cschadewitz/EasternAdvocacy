<?php
namespace Lasso\Petitions\Updates;

require_once getcwd().'/vendor/fzaninotto/faker/src/autoload.php';
use Seeder;
use Lasso\Petitions\Models\Petitions;
use Faker;

class SeedPetitionsTable extends Seeder{
    public function run(){
        $faker = Faker\Factory::Create();

        for($i = 0; $i < 100; $i++){
            $petition = new Petitions;
            $petition->title = $faker->sentence(5);
            $petition->summary = $faker->text(50);
            $petition->body = $faker->text(250);
            $petition->goal = $faker->randomNumber(3);
            $petition->save();
        }
    }
}