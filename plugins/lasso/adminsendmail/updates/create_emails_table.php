<?php namespace Lasso\Adminsendmail\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateEmailsTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_adminsendmail_emails', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_adminsendmail_emails');
    }

}
