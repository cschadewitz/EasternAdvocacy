<?php namespace Lasso\Adminsendmail\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateEmailsAddViews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('lasso_adminsendmail_emails', function ($table) {
			$table->unsignedInteger("views");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('emails', function ($table) {
			$table->dropColumn("views");
		});
	}

}
