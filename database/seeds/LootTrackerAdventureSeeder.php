<?php

use Illuminate\Database\Seeder;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Item\ItemInterface;

class LootTrackerAdventureSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adventure = App::make(AdventureInterface::class);
        $itemRepo = App::make(ItemInterface::class);
        //Add Bandit Nest
        $data = array(
            'name' => 'Bandit Nest',
            'slot1' => array(
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '800'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '800'),
                array('item_id' => $itemRepo->byName('Saltpeter')->id, 'amount' => '100'),
                array('item_id' => $itemRepo->byName('Saltpeter')->id, 'amount' => '200'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '200'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '400')
            ),
            'slot2' => array(
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '800'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '800'),
                array('item_id' => $itemRepo->byName('Saltpeter')->id, 'amount' => '100'),
                array('item_id' => $itemRepo->byName('Saltpeter')->id, 'amount' => '200'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '200'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '400')
            ),
            'slot3' => array(
                array('item_id' => $itemRepo->byName('Copper Ore')->id, 'amount' => '1000')
            ),
            'slot4' => array(
                array('item_id' => $itemRepo->byName('Iron Ore')->id, 'amount' => '750')
            ),
            'slot5' => array(
                array('item_id' => $itemRepo->byName('Coal')->id, 'amount' => '1000')
            ),
            'slot6' => array(
                array('item_id' => $itemRepo->byName('Pinewood Log')->id, 'amount' => '1000')
            ),
            'slot7' => array(
                array('item_id' => $itemRepo->byName('Hardwood Log')->id, 'amount' => '750')
            ),
            'slot8' => array(
                array('item_id' => $itemRepo->byName('Horse')->id, 'amount' => '750')
            ),
            'slot9' => array(
                array('item_id' => $itemRepo->byName('Settler')->id, 'amount' => '350')
            ),
            'slot10' => array(
                array('item_id' => $itemRepo->byName('Brew')->id, 'amount' => '750')
            ),
            'slot11' => array(
                array('item_id' => $itemRepo->byName('Bread')->id, 'amount' => '708'),
                array('item_id' => $itemRepo->byName('Gold Coin')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Meat')->id, 'amount' => '400')
            ),
            'slot12' => array(
                array('item_id' => $itemRepo->byName('Bread')->id, 'amount' => '708'),
                array('item_id' => $itemRepo->byName('Gold Coin')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Meat')->id, 'amount' => '400')
            ),
            'slot13' => array(
                array('item_id' => $itemRepo->byName('Coal Deposit Refill')->id, 'amount' => '800'),
                array('item_id' => $itemRepo->byName('Copper Deposit Refill')->id, 'amount' => '800'),
                array('item_id' => $itemRepo->byName('Gold Deposit Refill')->id, 'amount' => '200'),
                array('item_id' => $itemRepo->byName('Iron Deposit Refill')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Meat Deposit Refill')->id, 'amount' => '400')
            ),
            'slot14' => array(
                array('item_id' => $itemRepo->byName('Blue Flowerbed')->id, 'amount' => '1'),
                array('item_id' => $itemRepo->byName('Broken Handcart')->id, 'amount' => '1'),
                array('item_id' => $itemRepo->byName('Nothing')->id, 'amount' => '1')
            ),
            'slot15' => array(
                array('item_id' => $itemRepo->byName('Return to the Bandit Nest')->id, 'amount' => '1'),
                array('item_id' => $itemRepo->byName('Nothing')->id, 'amount' => '1')
            ),
        );
        $adventure->create($data);

        //Add The Black Knights
        $data = array(
            'name' => 'The Black Knights',
            'slot1' => array(
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '900'),
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '1250'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '900'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '1200'),
                array('item_id' => $itemRepo->byName('Saltpeter')->id, 'amount' => '250'),
                array('item_id' => $itemRepo->byName('Saltpeter')->id, 'amount' => '500'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '250'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '500')
            ),
            'slot2' => array(
                array('item_id' => $itemRepo->byName('Copper Ore')->id, 'amount' => '750')
            ),
            'slot3' => array(
                array('item_id' => $itemRepo->byName('Iron Ore')->id, 'amount' => '600')
            ),
            'slot4' => array(
                array('item_id' => $itemRepo->byName('Coal')->id, 'amount' => '750')
            ),
            'slot5' => array(
                array('item_id' => $itemRepo->byName('Hardwood Log')->id, 'amount' => '600')
            ),
            'slot6' => array(
                array('item_id' => $itemRepo->byName('Horse')->id, 'amount' => '600')
            ),
            'slot7' => array(
                array('item_id' => $itemRepo->byName('Settler')->id, 'amount' => '150')
            ),
            'slot8' => array(
                array('item_id' => $itemRepo->byName('Brew')->id, 'amount' => '600')
            ),
            'slot9' => array(
                array('item_id' => $itemRepo->byName('Bread')->id, 'amount' => '165'),
                array('item_id' => $itemRepo->byName('Gold Coin')->id, 'amount' => '600'),
                array('item_id' => $itemRepo->byName('Meat')->id, 'amount' => '600')
            ),
            'slot10' => array(
                array('item_id' => $itemRepo->byName('Coal Deposit Refill')->id, 'amount' => '800'),
                array('item_id' => $itemRepo->byName('Gold Deposit Refill')->id, 'amount' => '200'),
                array('item_id' => $itemRepo->byName('Gold Deposit Refill')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Iron Deposit Refill')->id, 'amount' => '600'),
                array('item_id' => $itemRepo->byName('Meat Deposit Refill')->id, 'amount' => '600')
            ),
            'slot11' => array(
                array('item_id' => $itemRepo->byName('Coal Deposit Refill')->id, 'amount' => '800'),
                array('item_id' => $itemRepo->byName('Gold Deposit Refill')->id, 'amount' => '200'),
                array('item_id' => $itemRepo->byName('Gold Deposit Refill')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Iron Deposit Refill')->id, 'amount' => '600'),
                array('item_id' => $itemRepo->byName('Meat Deposit Refill')->id, 'amount' => '600')
            ),
            'slot12' => array(
                array('item_id' => $itemRepo->byName('Angel Monument')->id, 'amount' => '1'),
                array('item_id' => $itemRepo->byName('Dark Castle')->id, 'amount' => '1'),
                array('item_id' => $itemRepo->byName('Nothing')->id, 'amount' => '1')
            ),
        );
        $adventure->create($data);
    }
}