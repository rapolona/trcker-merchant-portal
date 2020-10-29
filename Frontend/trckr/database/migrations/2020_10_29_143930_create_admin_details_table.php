<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_details', function(Blueprint $table)
		{
			$table->char('admin_detail_id', 36)->primary();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('designation')->nullable();
			$table->string('email_address')->nullable();
			$table->string('contact_number')->nullable();
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
		Schema::drop('admin_details');
	}

}
