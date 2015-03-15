<?php
namespace Lasso\Petitions\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePetitionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscribers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->char('uuid', 13);
            $table->string('email', 255);
            $table->string('name', 255);
            $table->mediumInteger('zip');
            $table->enum('type', array('Student', 'Alumni', 'Friend', 'Other'));
            $table->timestamp('verificationDate')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('subscribers');
    }
}