<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempTransferMachinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('temp_transfer_machines', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('os_id');
			$table->string('os');
			$table->string('plant_from');
			$table->string('plant_to');
			$table->string('ses');
			
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
		Schema::drop('temp_transfer_machines');
	}

}
