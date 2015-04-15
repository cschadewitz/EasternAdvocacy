<?php namespace Lasso\LegLookup\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateLegislatorsTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_leglookup_legislators', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('openID');
            $table->unsignedInteger('state');
            $table->unsignedInteger('district');
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
        Schema::dropIfExists('lasso_leglookup_legislators');
    }

}
