<?php namespace Lasso\LegislativeLookup\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateLegislatorsTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_legislativelookup_legislators', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('uuid');
            $table->string('state');
            $table->string('district');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('party');
            $table->string('email');
            $table->string('photo_url');
            $table->string('office_phone');
            $table->string('url');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_legislativelookup_legislators');
    }

}
