<?php namespace Lasso\Actions\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateRepsTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_actions_reps', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_actions_reps');
    }

}
