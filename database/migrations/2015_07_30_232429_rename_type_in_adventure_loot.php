<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTypeInAdventureLoot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('adventure_loot', 'type')) {
            Schema::table('adventure_loot', function ($table) {
                $table->string('type')->nullable()->change();
            });
        }

        if (Schema::hasColumn('adventure_loot', 'type')) {
            Schema::table('adventure_loot', function ($table) {
                $table->renameColumn('type', 'type_old');
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
        if (!Schema::hasColumn('adventure_loot', 'type')) {
            Schema::table('adventure_loot', function ($table) {
                $table->renameColumn('type_old', 'type');
            });
        }
    }
}
