<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTaskDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('task_details', function(Blueprint $table)
		{
			$table->foreign('task_ticket_id', 'task_details_ibfk_1')->references('task_ticket_id')->on('task_tickets')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('task_question_id', 'task_details_ibfk_2')->references('task_question_id')->on('task_questions')->onUpdate('CASCADE')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('task_details', function(Blueprint $table)
		{
			$table->dropForeign('task_details_ibfk_1');
			$table->dropForeign('task_details_ibfk_2');
		});
	}

}
