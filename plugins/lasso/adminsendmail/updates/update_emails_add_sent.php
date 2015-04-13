<?php

//use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateEmailsAddSent extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('lasso_adminsendmail_emails', function ($table) {
			$table->boolean('sent');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('lasso_adminsendmail_emails', function ($table) {
			$table->dropColumn("sent");
		});
	}

}
