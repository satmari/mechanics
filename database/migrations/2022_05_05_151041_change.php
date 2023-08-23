<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Change extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('machines', function($table)
		 {
     		// $table->string('write_off_reason')->nullable();
     		// $table->string('buyer')->nullable();

     		// $table->string('give_doc')->nullable();
     		// $table->string('return_doc')->nullable();
		 });

		Schema::table('temp_give_machines', function($table)
		 {
     		// $table->string('give_doc')->nullable();
		 });

		Schema::table('temp_return_machines', function($table)
		 {
     		// $table->string('return_doc')->nullable();
		 });

		Schema::table('temp_transfer_machines', function($table)
		 {
     		// $table->string('transfer_doc')->nullable();
     		// $table->string('source');
     		// $table->dropColumn('source');
		 });

		Schema::table('transfer_machine_logs', function($table)
		 {
     		// $table->string('plant_from')->nullable();
			// $table->string('plant_to')->nullable();
		 });
		
			
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
