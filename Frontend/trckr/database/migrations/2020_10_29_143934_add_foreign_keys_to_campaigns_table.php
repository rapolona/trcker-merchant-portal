<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCampaignsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('campaigns', function(Blueprint $table)
		{
			$table->foreign('merchant_id', 'campaigns_ibfk_1')->references('merchant_id')->on('merchants')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('campaigns', function(Blueprint $table)
		{
			$table->dropForeign('campaigns_ibfk_1');
		});
	}

}
