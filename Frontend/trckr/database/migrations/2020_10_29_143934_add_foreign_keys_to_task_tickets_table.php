<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTaskTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('task_tickets', function(Blueprint $table)
		{
			$table->foreign('campaign_id', 'task_tickets_ibfk_1')->references('campaign_id')->on('campaigns')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'task_tickets_ibfk_2')->references('user_id')->on('users')->onUpdate('CASCADE')->onDelete('NO ACTION');
			$table->foreign('branch_id', 'task_tickets_ibfk_3')->references('branch_id')->on('branches')->onUpdate('CASCADE')->onDelete('NO ACTION');
			$table->foreign('task_classification_id', 'task_tickets_ibfk_4')->references('task_classification_id')->on('task_classifications')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('task_tickets', function(Blueprint $table)
		{
			$table->dropForeign('task_tickets_ibfk_1');
			$table->dropForeign('task_tickets_ibfk_2');
			$table->dropForeign('task_tickets_ibfk_3');
			$table->dropForeign('task_tickets_ibfk_4');
		});
	}

}
