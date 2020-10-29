<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCampaignRewardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('campaign_rewards', function(Blueprint $table)
		{
			$table->foreign('campaign_id', 'campaign_rewards_ibfk_1')->references('campaign_id')->on('campaigns')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('campaign_rewards', function(Blueprint $table)
		{
			$table->dropForeign('campaign_rewards_ibfk_1');
		});
	}

}
