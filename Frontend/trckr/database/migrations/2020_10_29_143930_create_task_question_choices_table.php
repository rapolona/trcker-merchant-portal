<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskQuestionChoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_question_choices', function(Blueprint $table)
		{
			$table->char('choices_id', 36)->primary();
			$table->string('choices_value');
			$table->char('task_question_id', 36)->index('task_question_id');
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
		Schema::drop('task_question_choices');
	}

}
