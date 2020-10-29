<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_details', function(Blueprint $table)
		{
			$table->char('task_detail_id', 36)->unique('task_detail_id');
			$table->text('response');
			$table->char('task_ticket_id', 36)->index('task_ticket_id');
			$table->dateTime('createdAt');
			$table->dateTime('updatedAt');
			$table->char('task_question_id', 36)->nullable()->index('task_question_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('task_details');
	}

}
