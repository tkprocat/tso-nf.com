<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceitemPriceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('price_item_price', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('price_item_id')->unsigned();
			$table->foreign('price_item_id')->references('id')->on('price_item');
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
		Schema::drop('price_item_price');
	}

}
