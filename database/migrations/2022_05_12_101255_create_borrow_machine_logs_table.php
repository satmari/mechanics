<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowMachineLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('borrow_machine_logs', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('os_id');
			$table->string('os');
			$table->string('brand')->nullable();
			$table->string('type')->nullable();
			$table->string('code')->nullable();
			
			$table->string('function');
			$table->string('destination');
			
			$table->string('doc')->nullable();

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
		Schema::drop('borrow_machine_logs');
	}

}
