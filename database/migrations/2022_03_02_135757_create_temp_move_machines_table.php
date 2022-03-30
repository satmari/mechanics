<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempMoveMachinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('temp_move_machines', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('os_id');
			$table->string('os');
			$table->string('location')->nullable();
			$table->string('ses');
			$table->string('new_location_id');
			$table->string('new_location');
			$table->string('new_area');
			$table->string('new_plant');
			
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
		Schema::drop('temp_move_machines');
	}

}
