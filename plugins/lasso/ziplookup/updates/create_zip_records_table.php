<?php namespace Lasso\ZipLookup\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateZipRecordsTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_ziplookup_zip_records', function($table)
        {
            $table->engine = 'InnoDB';
            $table->mediumInteger('zip');
            $table->integer('representative_id');
            $table->timestamps();
            $table->primary(array('zip', 'representative_id'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_ziplookup_zip_records');
    }

}
