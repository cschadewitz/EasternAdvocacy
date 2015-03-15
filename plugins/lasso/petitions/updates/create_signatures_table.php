<?php
    namespace Lasso\Petitions\Updates;

    use Schema;
    use October\Rain\Database\Updates\Migration;

    class CreateSignaturesTable extends Migration
    {
        public function up()
        {
            Schema::create('lasso_petitions_signatures', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('sid');
                $table->string('name');
                $table->string('email');
                $table->text('address');
                $table->string('city');
                $table->string('zip');
                $table->integer('pid')->unsigned();
                $table->foreign('pid')->references('pid')->on('lasso_petitions_petitions')->onDelete('cascade');
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::drop('lasso_petitions_petitions');
        }
    }