<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempReturnMachinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('temp_return_machines', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('os_id');
			$table->string('os');
			$table->string('brand')->nullable();
			$table->string('type')->nullable();
			$table->string('code')->nullable();
			$table->string('return_machine_to');
			$table->string('ses');
			$table->string('return_doc')->nullable();

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('temp_return_machines');
	}

}
