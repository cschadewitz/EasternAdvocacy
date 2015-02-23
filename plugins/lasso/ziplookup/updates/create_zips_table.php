<?php namespace Lasso\ZipLookup\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateZipsTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_ziplookup_zips', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_ziplookup_zips');
    }

}
