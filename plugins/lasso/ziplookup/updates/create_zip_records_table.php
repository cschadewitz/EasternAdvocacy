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
            $table->unsignedInteger('representative_id');
            $table->mediumInteger('zip');
            $table->timestamps();
            $table->primary(array('zip', 'representative_id'));
            $table->foreign('representative_id')
                ->references('id')
                ->on('lasso_ziplookup_reps')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_ziplookup_zip_records');
    }

}
