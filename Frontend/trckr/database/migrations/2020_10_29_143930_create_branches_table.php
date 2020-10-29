<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('branches', function(Blueprint $table)
		{
			$table->char('branch_id', 36)->primary();
			$table->string('name', 64)->unique('name');
			$table->string('address')->nullable();
			$table->string('city', 64)->nullable();
			$table->string('latitude', 64)->nullable();
			$table->string('longitude', 64)->nullable();
			$table->text('photo_url')->nullable();
			$table->char('merchant_id', 36)->index('merchant_id');
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
		Schema::drop('branches');
	}

}
