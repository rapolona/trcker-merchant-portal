<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUserSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_sessions', function(Blueprint $table)
		{
			$table->foreign('user_id', 'user_sessions_ibfk_1')->references('user_id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_sessions', function(Blueprint $table)
		{
			$table->dropForeign('user_sessions_ibfk_1');
		});
	}

}
