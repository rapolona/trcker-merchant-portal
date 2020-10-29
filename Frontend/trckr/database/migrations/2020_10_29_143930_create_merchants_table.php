<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('merchants', function(Blueprint $table)
		{
			$table->char('merchant_id', 36)->primary();
			$table->string('name')->unique('name');
			$table->string('address')->nullable();
			$table->string('trade_name', 64)->nullable();
			$table->string('sector', 64)->nullable();
			$table->string('business_structure', 64)->nullable();
			$table->string('authorized_representative', 64)->nullable();
			$table->string('position', 64)->nullable();
			$table->string('contact_person', 64)->nullable();
			$table->string('contact_number', 64)->nullable();
			$table->string('email_address', 64)->nullable();
			$table->string('business_nature', 64)->nullable();
			$table->string('product_type', 64)->nullable();
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
		Schema::drop('merchants');
	}

}
