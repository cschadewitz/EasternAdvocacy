<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepresentativesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('representatives', function (Blueprint $table) {
			$table->increments('id');
	        $table->string('firstName');
	        $table->string('lastName');
	        $table->string('politicalParty');			
			$table->string('emailAddress');
            $table->string('phoneNumber');
            $table->string('physicalAddress');
            $table->date('expireDate');
	        $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('representatives');
	}

}
