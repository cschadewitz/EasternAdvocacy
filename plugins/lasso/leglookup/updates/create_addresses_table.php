<?php namespace Lasso\LegLookup\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateAddressesTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_leglookup_addresses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('address');
            $table->string('city');
            $table->unsignedInteger('stateID');
            $table->unsignedInteger('zip');
            $table->float('lat');
            $table->float('long');
            $table->unsignedInteger('legDist');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_leglookup_addresses');
    }

}
