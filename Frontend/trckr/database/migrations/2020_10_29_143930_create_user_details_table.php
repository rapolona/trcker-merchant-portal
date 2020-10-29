<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_details', function(Blueprint $table)
		{
			$table->char('user_detail_id', 36)->primary();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('account_level')->nullable();
			$table->string('email')->nullable();
			$table->char('user_id', 36)->index('user_id');
			$table->dateTime('createdAt');
			$table->dateTime('updatedAt');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_details');
	}

}
