<?php namespace Lasso\Actions\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateActionTakensTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_actions_action_takens', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('action_id');
            $table->string('name');
            $table->string('email');
            $table->string('ip_address');
            $table->string('zipcode');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_actions_action_takens');
    }

}
