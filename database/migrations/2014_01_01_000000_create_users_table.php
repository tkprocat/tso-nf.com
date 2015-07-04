<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username');
                $table->string('email')->unique();
                $table->string('password', 60);
                $table->boolean('activated');
                $table->rememberToken();
                $table->string('activation_code', 255)->nullable();
                $table->timestamps();
                $table->integer('guild_id')->default(0)->unsigned();
            });
        } else {
            if (!Schema::hasColumn('users', 'remember_token')) {
                Schema::table('users', function ($table) {
                    $table->rememberToken();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop('users');
    }

}
