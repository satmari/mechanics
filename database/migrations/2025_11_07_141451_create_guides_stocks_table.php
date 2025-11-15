<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuidesStocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('guides_stocks', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('guide_id');
			$table->string('plant');
			$table->integer('qty');
			$table->string('comment')->nullable();
			$table->string('type')->nullable();

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
		Schema::drop('guides_stocks');
	}

}
