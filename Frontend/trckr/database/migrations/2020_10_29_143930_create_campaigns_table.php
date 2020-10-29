<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('campaigns', function(Blueprint $table)
		{
			$table->char('campaign_id', 36)->primary();
			$table->char('merchant_id', 36)->index('merchant_id');
			$table->string('campaign_name', 64)->nullable();
			$table->string('campaign_description')->nullable();
			$table->dateTime('start_date')->nullable();
			$table->dateTime('end_date')->nullable();
			$table->integer('audience_age_min')->nullable();
			$table->integer('audience_age_max')->nullable();
			$table->string('audience_gender', 64)->nullable();
			$table->string('allowed_account_level', 64)->nullable();
			$table->float('budget', 10, 0)->nullable();
			$table->boolean('super_shoppers')->nullable();
			$table->boolean('allow_everyone')->nullable();
			$table->boolean('status')->nullable();
			$table->string('campaign_type', 64)->nullable();
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
		Schema::drop('campaigns');
	}

}
