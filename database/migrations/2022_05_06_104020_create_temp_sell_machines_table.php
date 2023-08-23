<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempSellMachinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('temp_sell_machines', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('os_id');
			$table->string('os');
			$table->string('brand')->nullable();
			$table->string('type')->nullable();
			$table->string('code')->nullable();
			$table->string('buyer')->nullable();
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
		Schema::drop('temp_sell_machines');
	}

}
