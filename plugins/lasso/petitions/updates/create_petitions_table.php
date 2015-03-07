<?php
    namespace Lasso\Petitions\Updates;

    use Schema;
    use October\Rain\Database\Updates\Migration;

    class CreatePetitionsTable extends Migration
    {
        public function up()
        {
            Schema::create('petitions', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('pid');
                $table->string('title');
                $table->string('slug')->index();
                $table->text('summary')->nullable();
                $table->text('body');
                $table->boolean('published')->default(false);
                $table->timestamp('publicationDate')->nullable();
                $table->integer('signatures')->default(0);
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::drop('petitions');
        }
    }