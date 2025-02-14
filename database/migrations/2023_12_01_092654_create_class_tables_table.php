<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassTablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('class_tables', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('IntKey')->unique();
			$table->string('brand');
			$table->string('code');
			$table->string('class');

			$table->string('image')->nullable();
			
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
		Schema::drop('class_tables');
	}

}
