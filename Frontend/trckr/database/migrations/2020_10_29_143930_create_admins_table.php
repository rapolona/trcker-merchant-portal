<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admins', function(Blueprint $table)
		{
			$table->char('admin_id', 36)->primary();
			$table->string('username')->unique('username');
			$table->string('password')->nullable();
			$table->string('password_salt')->nullable();
			$table->dateTime('createdAt');
			$table->dateTime('updatedAt');
			$table->char('merchant_id', 36)->nullable()->index('merchant_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('admins');
	}

}
