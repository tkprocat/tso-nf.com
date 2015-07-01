<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAdventureLootTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_adventure_loot', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('user_adventure_id')->unsigned();
            $table->integer('adventure_loot_id')->unsigned();
            $table->index('user_adventure_id');
            $table->index('adventure_loot_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_adventure_loot');
	}

}
