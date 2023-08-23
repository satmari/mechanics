<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('machines', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('os')->unique();
			$table->string('brand');
			$table->string('code');
			$table->string('type')->nullable();
			$table->string('remark_su')->nullable();
			$table->string('remark_ki')->nullable();

			$table->string('location')->nullable();
			$table->integer('location_id')->nullable();

			$table->string('machine_status');

			$table->string('inteos_status')->nullable();
			$table->string('inteos_line')->nullable();
			$table->string('inteos_machine_status')->nullable();

			$table->string('flag')->nullable();
			$table->integer('flag_id')->nullable();

			$table->string('racing')->nullable();

			$table->string('write_off_reason')->nullable();
			$table->string('buyer')->nullable();
			$table->string('give_doc')->nullable();
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
		Schema::drop('machines');
	}

}
