<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_adventure_loot', function($table)
		{
			$table->index('user_adventure_id');
			$table->index('adventure_loot_id');
		});

		Schema::table('user_adventure', function($table)
		{
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
		Schema::table('user_adventure_loot', function($table)
		{
	      $table->dropIndex('user_adventure_loot_user_adventure_id_index');
		  $table->dropIndex('user_adventure_loot_adventure_loot_id_index');
		});

		Schema::table('user_adventure', function($table)
		{
			$table->dropIndex('user_adventure_adventure_id_index');
			$table->dropIndex('user_adventure_user_id_index');
		});
	}

}