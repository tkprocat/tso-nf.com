<?php

use Illuminate\Database\Seeder;

class LootTrackerUserAdventureSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $loot = App::make('LootTracker\Repositories\Loot\LootInterface');
        $data = array(
            'user_id' => 2,
            'adventure_id' => '1',
            'slot1' => '1',
            'slot2' => '7',
            'slot3' => '9',
            'slot4' => '13',
            'slot5' => '17',
            'slot6' => '21',
            'slot7' => '1',
            'slot8' => '27'
        );
        $loot->create($data);
    }

}