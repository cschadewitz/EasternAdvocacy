<?php namespace Lasso\Subscribe\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateUserExtensionsTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_subscribe_user_extensions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned->index;
            $table->enum('affiliation', array('Student', 'Alumni', 'Friend', 'Other'));
            $table->timestamp('verificationDate')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_subscribe_user_extensions');
    }

}
