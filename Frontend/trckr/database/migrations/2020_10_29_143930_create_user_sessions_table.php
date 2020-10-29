<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_sessions', function(Blueprint $table)
		{
			$table->char('user_session_id', 36)->primary();
			$table->string('token')->nullable();
			$table->dateTime('createdAt');
			$table->dateTime('updatedAt');
			$table->char('user_id', 36)->nullable()->index('user_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_sessions');
	}

}
