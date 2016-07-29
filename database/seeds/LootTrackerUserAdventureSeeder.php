<?php

use Illuminate\Database\Seeder;
use LootTracker\Repositories\Loot\LootInterface;

class LootTrackerUserAdventureSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lootRepo = App::make(LootInterface::class);

        //Bandit Nest - User1 - 1
        $adventure_id = 1;
        $data = array(
            'user_id' => 2,
            'adventure_id' => $adventure_id,
            'slot1' => $lootRepo->getLootByNameAndAmount($adventure_id, 1, 'Exotic Wood Log', 400)->id,
            'slot2' => $lootRepo->getLootByNameAndAmount($adventure_id, 2, 'Exotic Wood Log', 800)->id,
            'slot3' => $lootRepo->getLootByNameAndAmount($adventure_id, 3, 'Copper Ore', 1000)->id,
            'slot4' => $lootRepo->getLootByNameAndAmount($adventure_id, 4, 'Iron Ore', 750)->id,
            'slot5' => $lootRepo->getLootByNameAndAmount($adventure_id, 5, 'Coal', 1000)->id,
            'slot6' => $lootRepo->getLootByNameAndAmount($adventure_id, 6, 'Pinewood Log', 1000)->id,
            'slot7' => $lootRepo->getLootByNameAndAmount($adventure_id, 7, 'Hardwood Log', 750)->id,
            'slot8' => $lootRepo->getLootByNameAndAmount($adventure_id, 8, 'Horse', 750)->id,
            'slot9' => $lootRepo->getLootByNameAndAmount($adventure_id, 9, 'Settler', 350)->id,
            'slot10' => $lootRepo->getLootByNameAndAmount($adventure_id, 10, 'Brew', 750)->id,
            'slot11' => $lootRepo->getLootByNameAndAmount($adventure_id, 11, 'Meat', 400)->id,
            'slot12' => $lootRepo->getLootByNameAndAmount($adventure_id, 12, 'Bread', 708)->id,
            'slot13' => $lootRepo->getLootByNameAndAmount($adventure_id, 13, 'Gold Deposit Refill', 200)->id,
            'slot14' => $lootRepo->getLootByNameAndAmount($adventure_id, 14, 'Nothing', 1)->id,
            'slot15' => $lootRepo->getLootByNameAndAmount($adventure_id, 15, 'Nothing', 1)->id,
        );
        $lootRepo->create($data);

        //The Black Knight - User1 - 1
        $adventure_id = 2;
        $data = array(
            'user_id' => 2,
            'adventure_id' => $adventure_id,
            'slot1' => $lootRepo->getLootByNameAndAmount($adventure_id, 1, 'Granite', 900)->id,
            'slot2' => $lootRepo->getLootByNameAndAmount($adventure_id, 2, 'Copper Ore', 750)->id,
            'slot3' => $lootRepo->getLootByNameAndAmount($adventure_id, 3, 'Iron Ore', 600)->id,
            'slot4' => $lootRepo->getLootByNameAndAmount($adventure_id, 4, 'Coal', 750)->id,
            'slot5' => $lootRepo->getLootByNameAndAmount($adventure_id, 5, 'Hardwood Log', 600)->id,
            'slot6' => $lootRepo->getLootByNameAndAmount($adventure_id, 6, 'Horse', 600)->id,
            'slot7' => $lootRepo->getLootByNameAndAmount($adventure_id, 7, 'Settler', 150)->id,
            'slot8' => $lootRepo->getLootByNameAndAmount($adventure_id, 8, 'Brew', 600)->id,
            'slot9' => $lootRepo->getLootByNameAndAmount($adventure_id, 9, 'Gold Coin', 600)->id,
            'slot10' => $lootRepo->getLootByNameAndAmount($adventure_id, 10, 'Gold Deposit Refill', 400)->id,
            'slot11' => $lootRepo->getLootByNameAndAmount($adventure_id, 11, 'Meat Deposit Refill', 600)->id,
            'slot12' => $lootRepo->getLootByNameAndAmount($adventure_id, 12, 'Nothing', 1)->id
        );
        $lootRepo->create($data);

        //Bandit Nest - User1 - 3
        $adventure_id = 1;
        $data = array(
            'user_id' => 2,
            'adventure_id' => $adventure_id,
            'slot1' => $lootRepo->getLootByNameAndAmount($adventure_id, 1, 'Exotic Wood Log', 400)->id,
            'slot2' => $lootRepo->getLootByNameAndAmount($adventure_id, 2, 'Granite', 800)->id,
            'slot3' => $lootRepo->getLootByNameAndAmount($adventure_id, 3, 'Copper Ore', 1000)->id,
            'slot4' => $lootRepo->getLootByNameAndAmount($adventure_id, 4, 'Iron Ore', 750)->id,
            'slot5' => $lootRepo->getLootByNameAndAmount($adventure_id, 5, 'Coal', 1000)->id,
            'slot6' => $lootRepo->getLootByNameAndAmount($adventure_id, 6, 'Pinewood Log', 1000)->id,
            'slot7' => $lootRepo->getLootByNameAndAmount($adventure_id, 7, 'Hardwood Log', 750)->id,
            'slot8' => $lootRepo->getLootByNameAndAmount($adventure_id, 8, 'Horse', 750)->id,
            'slot9' => $lootRepo->getLootByNameAndAmount($adventure_id, 9, 'Settler', 350)->id,
            'slot10' => $lootRepo->getLootByNameAndAmount($adventure_id, 10, 'Brew', 750)->id,
            'slot11' => $lootRepo->getLootByNameAndAmount($adventure_id, 11, 'Meat', 400)->id,
            'slot12' => $lootRepo->getLootByNameAndAmount($adventure_id, 12, 'Bread', 708)->id,
            'slot13' => $lootRepo->getLootByNameAndAmount($adventure_id, 13, 'Gold Deposit Refill', 200)->id,
            'slot14' => $lootRepo->getLootByNameAndAmount($adventure_id, 14, 'Nothing', 1)->id,
            'slot15' => $lootRepo->getLootByNameAndAmount($adventure_id, 15, 'Nothing', 1)->id,
        );

        //Fix to move data a week back.
        $loot = $lootRepo->create($data);
        $loot->created_at = \Carbon\Carbon::now()->subDays(7);
        $loot->save();
    }
}