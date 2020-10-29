<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskClassificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_classifications', function(Blueprint $table)
		{
			$table->char('task_classification_id', 36)->unique('task_classification_id');
			$table->string('name', 64)->unique('name');
			$table->string('description')->nullable();
			$table->string('task_type', 64);
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
		Schema::drop('task_classifications');
	}

}
