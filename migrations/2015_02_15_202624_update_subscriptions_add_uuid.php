<?php

//use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateSubscriptionsAddUuid extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('subscribers', function($table) {
            $table->string('uuid')->unique();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('subscribers', function($table) {
            $table->dropColumn('uuid');
        });
	}

}
