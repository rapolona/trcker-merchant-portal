<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTaskQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('task_questions', function(Blueprint $table)
		{
			$table->foreign('task_id', 'task_questions_ibfk_1')->references('task_id')->on('tasks')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('task_questions', function(Blueprint $table)
		{
			$table->dropForeign('task_questions_ibfk_1');
		});
	}

}
