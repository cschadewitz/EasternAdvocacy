<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZipRepresentativesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zip_representatives', function (Blueprint $table) {
			$table->mediumInteger('zip');
	        $table->integer('representative_id');
	        $table->timestamps();
	        $table->primary(array('zip', 'representative_id'));
			$table->foreign('representative_id')
                ->references('id')
                ->on('lasso_ziplookup_reps')
                ->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('zip_representatives');
	}

}
