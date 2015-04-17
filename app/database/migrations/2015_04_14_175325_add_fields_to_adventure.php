<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToAdventure extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('adventure', function($table) {
            if (!Schema::hasColumn('adventure', 'type')) {
                $table->string('type')->default('');
            }
            if (!Schema::hasColumn('adventure', 'disabled')) {
                $table->boolean('disabled')->default(0);
            }
        });
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('adventure', function($table) {
            if (!Schema::hasColumn('adventure', 'type'))
                $table->dropColumn('type');
            if (!Schema::hasColumn('adventure', 'disabled'))
                $table->dropColumn('disabled');
        });
	}

}
