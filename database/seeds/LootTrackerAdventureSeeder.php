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
        $data = array(
            'name' => 'Bandit Nest',
            'slot1' => array(
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '1100'),
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '1300'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '600'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '700'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '300'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '400')
            ),
            'slot2' => array(
                array('item_id' => $itemRepo->byName('Hardwood Plank')->id, 'amount' => '1000'),
                array('item_id' => $itemRepo->byName('Marble')->id, 'amount' => '1000')
            ),
            'slot3' => array(
                array('item_id' => $itemRepo->byName('Iron Sword')->id, 'amount' => '1600'),
                array('item_id' => $itemRepo->byName('Horse')->id, 'amount' => '1600'),
                array('item_id' => $itemRepo->byName('Longbow')->id, 'amount' => '1800'),
                array('item_id' => $itemRepo->byName('Steel Sword')->id, 'amount' => '800')
            ),
            'slot4' => array(
                array('item_id' => $itemRepo->byName('Iron Sword')->id, 'amount' => '1600'),
                array('item_id' => $itemRepo->byName('Horse')->id, 'amount' => '1600'),
                array('item_id' => $itemRepo->byName('Longbow')->id, 'amount' => '1800'),
                array('item_id' => $itemRepo->byName('Steel Sword')->id, 'amount' => '800')
            ),
            'slot5' => array(
                array('item_id' => $itemRepo->byName('Brew')->id, 'amount' => '700'),
                array('item_id' => $itemRepo->byName('Bread')->id, 'amount' => '500'),
                array('item_id' => $itemRepo->byName('Sausage')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Settler')->id, 'amount' => '400')
            ),
            'slot6' => array(
                array('item_id' => $itemRepo->byName('Wheat Refill')->id, 'amount' => '1800'),
                array('item_id' => $itemRepo->byName('Gold Ore Refill')->id, 'amount' => '500'),
                array('item_id' => $itemRepo->byName('Blue Flowerbed')->id, 'amount' => '1'),
                array('item_id' => $itemRepo->byName('Broken Handcart')->id, 'amount' => '1'),
                array('item_id' => $itemRepo->byName('Gold Coin')->id, 'amount' => '150'),
                array('item_id' => $itemRepo->byName('Gold Coin')->id, 'amount' => '300')
            ),
            'slot7' => array(

            ),
            'slot8' => array(
                array('item_id' => $itemRepo->byName('Return to Bandit Nest')->id, 'amount' => '1'),
                array('item_id' => $itemRepo->byName('Nothing')->id, 'amount' => '1')
            ),
        );

        $adventure->create($data);
	}

}