<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attachments', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('attachment_code')->unique();
			$table->string('attachment_description')->nullable();

			$table->integer('attachment_type_id')->nullable();
			$table->string('machine_class')->nullable();

			$table->string('style')->nullable();
			$table->string('operation')->nullable();
			$table->text('notes')->nullable();
			
			$table->integer('location_a_id')->nullable();
			$table->integer('suplier_id')->nullable();
			$table->string('calz_code')->nullable();
			
			$table->string('picture')->nullable();
			$table->string('video')->nullable();
			
			$table->string('status')->nullable();

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
		Schema::drop('attachments');
	}

}
