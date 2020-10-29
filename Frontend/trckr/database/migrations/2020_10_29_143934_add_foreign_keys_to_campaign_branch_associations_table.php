<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCampaignBranchAssociationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('campaign_branch_associations', function(Blueprint $table)
		{
			$table->foreign('campaign_id', 'campaign_branch_associations_ibfk_1')->references('campaign_id')->on('campaigns')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('branch_id', 'campaign_branch_associations_ibfk_2')->references('branch_id')->on('branches')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('campaign_branch_associations', function(Blueprint $table)
		{
			$table->dropForeign('campaign_branch_associations_ibfk_1');
			$table->dropForeign('campaign_branch_associations_ibfk_2');
		});
	}

}
