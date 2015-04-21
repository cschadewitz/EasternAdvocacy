<?php namespace Lasso\AutoGenMail\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateAGMailsTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_autogenmail_a_g_mails', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('subject');
            $table->integer('template_id');
            $table->boolean('require_user');
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_autogenmail_a_g_mails');
    }

}
