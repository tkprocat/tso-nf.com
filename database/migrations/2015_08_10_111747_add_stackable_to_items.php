<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStackableToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('items', 'stackable')) {
            Schema::table('items', function ($table) {
                $table->boolean('stackable')->default(1);
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
        if (Schema::hasColumn('items', 'stackable')) {
            Schema::table('items', function ($table) {
                $table->dropColumn('stackable');
            });
        }
    }
}
