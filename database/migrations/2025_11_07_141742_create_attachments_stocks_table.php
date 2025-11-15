<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsStocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attachments_stocks', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('attachment_id');
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
		Schema::drop('attachments_stocks');
	}

}
