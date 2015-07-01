<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAdventureTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_adventure', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('adventure_id')->unsigned();
            $table->integer('user_id')->unsigned();
			$table->timestamps();
            $table->foreign('adventure_id')->references('id')->on('adventure')->onDelete('cascade');
            $table->index('adventure_id');
            $table->index('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_adventure');
	}

}
