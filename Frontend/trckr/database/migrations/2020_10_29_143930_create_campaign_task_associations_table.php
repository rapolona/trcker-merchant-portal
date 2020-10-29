<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignTaskAssociationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('campaign_task_associations', function(Blueprint $table)
		{
			$table->char('campaign_task_association_id', 36)->primary();
			$table->char('campaign_id', 36);
			$table->integer('index');
			$table->char('task_id', 36)->index('task_id');
			$table->dateTime('createdAt');
			$table->dateTime('updatedAt');
			$table->unique(['campaign_id','task_id'], 'campaign_task_associations_task_id_campaign_id_unique');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('campaign_task_associations');
	}

}
