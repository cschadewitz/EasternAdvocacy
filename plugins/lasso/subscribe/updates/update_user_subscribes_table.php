<?php namespace Lasso\Subscribe\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUserSubscribesTable extends Migration
{

    public function up()
    {
        Schema::table('lasso_subscribe_user_subscribes', function($table)
        {
            $table->enum('type', array('Student', 'Alumni', 'Friend', 'Other'));
        });
    }

    public function down()
    {
        Schema::dropDown(['type']);
    }

}
