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
			$table->dropIndex('user_adventure_id');
			$table->dropIndex('adventure_loot_id');
		});

		Schema::table('user_adventure', function($table)
		{
			$table->dropIndex('adventure_id');
			$table->dropIndex('user_id');
		});
	}

}
