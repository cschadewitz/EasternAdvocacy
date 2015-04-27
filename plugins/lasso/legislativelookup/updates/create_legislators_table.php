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
            $table->string('uuid')->index();
            $table->string('state');
            $table->string('district');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('party');
            $table->string('email')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_legislativelookup_legislators');
    }

}
