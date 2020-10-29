<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_sessions', function(Blueprint $table)
		{
			$table->char('admin_session_id', 36)->primary();
			$table->text('token')->nullable();
			$table->dateTime('createdAt');
			$table->dateTime('updatedAt');
			$table->char('admin_id', 36)->nullable()->index('admin_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('admin_sessions');
	}

}
