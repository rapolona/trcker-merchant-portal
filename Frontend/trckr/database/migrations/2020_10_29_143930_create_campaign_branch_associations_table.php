<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignBranchAssociationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('campaign_branch_associations', function(Blueprint $table)
		{
			$table->char('campaign_branch_association_id', 36)->primary();
			$table->char('campaign_id', 36);
			$table->char('branch_id', 36)->index('branch_id');
			$table->integer('respondent_count')->nullable();
			$table->dateTime('createdAt');
			$table->dateTime('updatedAt');
			$table->unique(['campaign_id','branch_id'], 'campaign_branch_associations_branch_id_campaign_id_unique');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('campaign_branch_associations');
	}

}
