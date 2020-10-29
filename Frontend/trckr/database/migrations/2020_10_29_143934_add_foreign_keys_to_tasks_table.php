<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tasks', function(Blueprint $table)
		{
			$table->foreign('merchant_id', 'tasks_ibfk_1')->references('merchant_id')->on('merchants')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('task_classification_id', 'tasks_ibfk_2')->references('task_classification_id')->on('task_classifications')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tasks', function(Blueprint $table)
		{
			$table->dropForeign('tasks_ibfk_1');
			$table->dropForeign('tasks_ibfk_2');
		});
	}

}
