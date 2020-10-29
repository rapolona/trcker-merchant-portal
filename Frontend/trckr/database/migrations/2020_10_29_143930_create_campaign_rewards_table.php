<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignRewardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('campaign_rewards', function(Blueprint $table)
		{
			$table->char('campaign_reward_id', 36)->primary();
			$table->char('campaign_id', 36)->index('campaign_id');
			$table->string('type', 64)->nullable();
			$table->string('reward_name', 64)->nullable();
			$table->string('reward_description')->nullable();
			$table->float('amount', 10, 0)->nullable();
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
		Schema::drop('campaign_rewards');
	}

}
