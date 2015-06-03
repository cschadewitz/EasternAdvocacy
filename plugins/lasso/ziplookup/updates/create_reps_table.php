<?php namespace Lasso\ZipLookup\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateRepsTable extends Migration
{

    public function up()
    {
        Schema::create('lasso_ziplookup_reps', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('title');
            $table->string('politicalParty');
            $table->string('emailAddress');
            $table->string('phoneNumber');
            $table->string('physicalAddress');
            $table->date('expireDate');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lasso_ziplookup_reps');
    }

}
