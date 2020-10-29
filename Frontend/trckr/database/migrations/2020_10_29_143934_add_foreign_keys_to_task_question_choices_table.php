<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTaskQuestionChoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('task_question_choices', function(Blueprint $table)
		{
			$table->foreign('task_question_id', 'task_question_choices_ibfk_1')->references('task_question_id')->on('task_questions')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('task_question_choices', function(Blueprint $table)
		{
			$table->dropForeign('task_question_choices_ibfk_1');
		});
	}

}
