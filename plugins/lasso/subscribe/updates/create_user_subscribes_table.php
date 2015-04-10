<?php namespace Lasso\Subscribe\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateUserSubscribesTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_subscribe_user_subscribes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->index;
            $table->timestamp('verificationDate')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_subscribe_user_subscribes');
    }

}
