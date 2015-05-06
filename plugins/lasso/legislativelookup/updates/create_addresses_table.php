<?php namespace Lasso\LegislativeLookup\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateAddressesTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_legislativelookup_addresses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->float('lat');
            $table->float('long');
            $table->string('district')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_legislativelookup_addresses');
    }

}
