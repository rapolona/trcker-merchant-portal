<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_questions', function(Blueprint $table)
		{
			$table->char('task_question_id', 36)->unique('task_question_id');
			$table->string('question');
			$table->integer('index');
			$table->string('required_inputs', 64)->nullable();
			$table->char('task_id', 36)->index('task_id');
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
		Schema::drop('task_questions');
	}

}
