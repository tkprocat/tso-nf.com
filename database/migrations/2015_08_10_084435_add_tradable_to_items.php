<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTradableToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('items', 'tradable')) {
            Schema::table('items', function ($table) {
                $table->boolean('tradable')->default(1);
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
        if (Schema::hasColumn('items', 'tradable')) {
            Schema::table('items', function ($table) {
                $table->dropColumn('tradable');
            });
        }
    }
}
