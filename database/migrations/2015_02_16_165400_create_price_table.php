<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('item_id')->unsigned();
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
			$table->integer('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
			$table->double('min_price');
			$table->double('avg_price');
			$table->double('max_price');
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
		Schema::drop('prices');
	}

}
