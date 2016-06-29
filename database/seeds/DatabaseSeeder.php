<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('EntrustSeeder');
        $this->call('UserSeeder');

        $this->call('ItemSeeder');
        $this->call('LootTrackerAdventureSeeder');
        $this->call('LootTrackerUserAdventureSeeder');
        $this->call('GuildSeeder');
        $this->call('BlogSeeder');
    }
}
