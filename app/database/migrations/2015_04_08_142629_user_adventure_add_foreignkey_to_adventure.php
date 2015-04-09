<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserAdventureAddForeignkeyToAdventure extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        //Yes, below aren't database independent, but I can't find a better way of doing it.
        DB::statement('ALTER TABLE user_adventure MODIFY COLUMN adventure_id INT(10) UNSIGNED NOT NULL');
        Schema::table('user_adventure', function($table) {
            $table->foreign('adventure_id')->references('id')->on('adventure')->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('user_adventure', function($table) {
            $table->dropForeign('user_adventure_adventure_id_foreign');
        });
        DB::statement('ALTER TABLE user_adventure MODIFY COLUMN adventure_id INT(11) NOT NULL');
	}

}
