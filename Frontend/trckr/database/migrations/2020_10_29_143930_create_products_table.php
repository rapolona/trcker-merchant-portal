<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->char('product_id', 36)->unique('product_id');
			$table->string('product_name', 64)->unique('product_name');
			$table->string('product_description');
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
		Schema::drop('products');
	}

}
