<?php

use October\Rain\Database\Updates\Migration;

class UpdateSubscriptionsNullifyVerification extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE `subscribers` MODIFY `verificationDate` datetime NULL;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::statement('ALTER TABLE `subscribers` MODIFY `verificationDate` datetime NOT NULL;');
	}

}
