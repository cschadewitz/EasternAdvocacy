<?php namespace Lasso\Actions\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateActionsTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_actions_actions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->string('subtitle');
            $table->text('description');
            $table->integer('template_id');
            $table->boolean('require_user');
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_actions_actions');
    }

}
