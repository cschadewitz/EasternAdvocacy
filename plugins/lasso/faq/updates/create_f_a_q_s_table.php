<?php namespace Lasso\FAQ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateFAQSTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_faq_f_a_q_s', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_faq_f_a_q_s');
    }

}
