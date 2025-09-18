<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuidesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('guides', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('guide_code')->unique();
			$table->string('guide_description')->nullable();

			$table->integer('guide_type_id')->nullable();
			$table->integer('suplier_id')->nullable();
			$table->string('machine_class')->nullable();
			$table->string('location')->nullable();
			$table->string('status')->nullable();

			$table->string('calz_code')->nullable();
			$table->string('fold')->nullable();
			$table->string('style')->nullable();
			$table->string('operation')->nullable();

			$table->float('entry_mm')->nullable();
			$table->float('exit_mm')->nullable();
			$table->string('tickness_mm')->nullable();
			$table->float('elastic_mm')->nullable();

			$table->text('note')->nullable();
			$table->string('picture')->nullable();
			$table->string('video')->nullable();
			
			$table->integer('qty_su')->nullable();
			$table->integer('qty_ki')->nullable();
			$table->integer('qty_se')->nullable();
			$table->integer('qty_valy')->nullable();


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
		Schema::drop('guides');
	}

}
