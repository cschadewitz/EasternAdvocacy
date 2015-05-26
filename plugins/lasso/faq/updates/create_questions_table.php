<?php namespace Lasso\FAQ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateQuestionsTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_faq_questions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('question');
            $table->text('answer');
            $table->integer('faq_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_faq_questions');
    }

}
