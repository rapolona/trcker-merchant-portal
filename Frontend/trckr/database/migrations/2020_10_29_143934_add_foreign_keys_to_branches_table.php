<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBranchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('branches', function(Blueprint $table)
		{
			$table->foreign('merchant_id', 'branches_ibfk_1')->references('merchant_id')->on('merchants')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('branches', function(Blueprint $table)
		{
			$table->dropForeign('branches_ibfk_1');
		});
	}

}
