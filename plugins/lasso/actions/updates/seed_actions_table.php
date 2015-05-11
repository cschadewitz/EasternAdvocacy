<?php
namespace Lasso\Actions\Updates;

require_once '/srv/october/vendor/fzaninotto/faker/src/autoload.php';
use Faker;
use Seeder;
use Lasso\Actions\Models\Action;

class SeedActionsTable extends Seeder
{
    public function run()
    {
        $faker = Faker\Factory::create();
 
        for ($i = 0; $i < 20; $i++)
        {
            $action = new Action;
            $action->title = $faker->sentence(3);
            $action->subtitle = $faker->sentence(5);
            $action->description = $faker->paragraph(4);
            $action->template_id = 8;
            $action->require_user = true;
            $action->is_active = $faker->numberBetween(0, 1);
            $action->save();
        }
    }
}