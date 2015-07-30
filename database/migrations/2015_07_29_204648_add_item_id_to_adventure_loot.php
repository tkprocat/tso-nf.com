<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemIdToAdventureLoot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('adventure_loot', 'item_id')) {
            Schema::table('adventure_loot', function ($table) {
                $table->integer('item_id')->unsigned()->nullable()->index();
                $table->foreign('item_id')->references('id')->on('items');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('adventure_loot', 'item_id')) {
            Schema::table('adventure_loot', function ($table) {
                $table->dropColumn('item_id');
            });
        }
    }
}
