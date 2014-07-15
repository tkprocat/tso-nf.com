<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdventureLootTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adventure_loot', function(Blueprint $table)
		{
			$table->increments('id');
            $table->tinyInteger('slot')->unsigned();
            $table->string('type');
            $table->integer('amount');
            $table->integer('adventure_id')->unsigned()->index();
            $table->foreign('adventure_id')->references('id')->on('adventure')->onDelete('cascade');
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
		Schema::drop('adventure_loot');
	}
}
