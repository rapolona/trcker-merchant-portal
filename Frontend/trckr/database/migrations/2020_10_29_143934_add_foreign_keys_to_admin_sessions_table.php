<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAdminSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('admin_sessions', function(Blueprint $table)
		{
			$table->foreign('admin_id', 'admin_sessions_ibfk_1')->references('admin_id')->on('admins')->onUpdate('CASCADE')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('admin_sessions', function(Blueprint $table)
		{
			$table->dropForeign('admin_sessions_ibfk_1');
		});
	}

}
