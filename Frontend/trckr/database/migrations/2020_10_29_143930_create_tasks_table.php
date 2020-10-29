<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->char('task_id', 36)->unique('task_id');
			$table->string('task_name');
			$table->string('task_description')->nullable();
			$table->string('subject_level', 64)->nullable();
			$table->text('banner_image')->nullable();
			$table->char('merchant_id', 36)->nullable()->index('merchant_id');
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
		Schema::drop('tasks');
	}

}
