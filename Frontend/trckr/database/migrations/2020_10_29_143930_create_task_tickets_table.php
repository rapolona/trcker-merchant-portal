<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_tickets', function(Blueprint $table)
		{
			$table->char('task_ticket_id', 36)->unique('task_ticket_id');
			$table->string('device_id');
			$table->string('approval_status', 64)->nullable();
			$table->char('campaign_id', 36)->index('campaign_id');
			$table->char('user_id', 36)->index('user_id');
			$table->char('branch_id', 36)->index('branch_id');
			$table->char('task_classification_id', 36)->index('task_classification_id');
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
		Schema::drop('task_tickets');
	}

}
