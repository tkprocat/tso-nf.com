<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstimatedValueToAdventure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('adventure', 'estimated_value')) {
            Schema::table('adventure', function ($table) {
                $table->decimal('estimated_value')->default(0);
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
        if (Schema::hasColumn('adventure', 'estimated_value')) {
            Schema::table('adventure', function ($table) {
                $table->dropColumn('estimated_value');
            });
        }
    }
}
