<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCampaignTaskAssociationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('campaign_task_associations', function(Blueprint $table)
		{
			$table->foreign('campaign_id', 'campaign_task_associations_ibfk_1')->references('campaign_id')->on('campaigns')->onUpdate('CASCADE')->onDelete('NO ACTION');
			$table->foreign('task_id', 'campaign_task_associations_ibfk_2')->references('task_id')->on('tasks')->onUpdate('CASCADE')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('campaign_task_associations', function(Blueprint $table)
		{
			$table->dropForeign('campaign_task_associations_ibfk_1');
			$table->dropForeign('campaign_task_associations_ibfk_2');
		});
	}

}
