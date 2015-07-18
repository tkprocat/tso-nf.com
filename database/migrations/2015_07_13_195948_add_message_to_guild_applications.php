<?php

use Illuminate\Database\Migrations\Migration;

class AddMessageToGuildApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('guild_applications', 'message')) {
            Schema::table('guild_applications', function ($table) {
                $table->text('message')->nullable();
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
        if (Schema::hasColumn('guild_applications', 'message')) {
            Schema::table('guild_applications', function ($table) {
                $table->dropColumn('message');
            });
        }
    }
}
