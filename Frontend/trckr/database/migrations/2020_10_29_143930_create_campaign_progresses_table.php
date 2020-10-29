<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignProgressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('campaign_progresses', function(Blueprint $table)
		{
			$table->char('campaign_progress_id', 36)->primary();
			$table->char('campaign_id', 36)->index('campaign_id');
			$table->char('user_id', 36)->index('user_id');
			$table->string('mission_status')->nullable();
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
		Schema::drop('campaign_progresses');
	}

}
