<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCampaignProgressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('campaign_progresses', function(Blueprint $table)
		{
			$table->foreign('campaign_id', 'campaign_progresses_ibfk_1')->references('campaign_id')->on('campaigns')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'campaign_progresses_ibfk_2')->references('user_id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('campaign_progresses', function(Blueprint $table)
		{
			$table->dropForeign('campaign_progresses_ibfk_1');
			$table->dropForeign('campaign_progresses_ibfk_2');
		});
	}

}
