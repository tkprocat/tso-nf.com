<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricelistItemPriceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pricelist_item_price', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('pricelist_item_id')->unsigned();
			$table->foreign('pricelist_item_id')->references('id')->on('pricelist_item')->onDelete('cascade');
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
		Schema::drop('pricelist_item_price');
	}

}
