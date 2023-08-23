<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempAdjustMachinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('temp_adjust_machines', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('os_id');
			$table->string('os');
			$table->string('brand')->nullable();
			$table->string('type')->nullable();
			$table->string('code')->nullable();
			$table->string('adjust_machine_to');
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
		Schema::drop('temp_adjust_machines');
	}

}
