<?php

class LootTrackerAdventureSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        $adventure = App::make('LootTracker\Adventure\AdventureInterface');
        $data = array(
            'name' => 'Bandit Nest',
            'slot1' => array(
                array('type' => 'Exotic Wood Log', 'amount' => '1100'),
                array('type' => 'Exotic Wood Log', 'amount' => '1300'),
                array('type' => 'Granite', 'amount' => '600'),
                array('type' => 'Granite', 'amount' => '700'),
                array('type' => 'Titanium Ore', 'amount' => '300'),
                array('type' => 'Titanium Ore', 'amount' => '400')
            ),
            'slot2' => array(
                array('type' => 'Hardwood Plank', 'amount' => '1000'),
                array('type' => 'Marble', 'amount' => '1000')
            ),
            'slot3' => array(
                array('type' => 'Iron Sword', 'amount' => '1600'),
                array('type' => 'Horse', 'amount' => '1600'),
                array('type' => 'Longbow', 'amount' => '1800'),
                array('type' => 'Steel Sword', 'amount' => '800')
            ),
            'slot4' => array(
                array('type' => 'Iron Sword', 'amount' => '1600'),
                array('type' => 'Horse', 'amount' => '1600'),
                array('type' => 'Longbow', 'amount' => '1800'),
                array('type' => 'Steel Sword', 'amount' => '800')
            ),
            'slot5' => array(
                array('type' => 'Brew', 'amount' => '700'),
                array('type' => 'Bread', 'amount' => '500'),
                array('type' => 'Sausage', 'amount' => '400'),
                array('type' => 'Settler', 'amount' => '400')
            ),
            'slot6' => array(
                array('type' => 'Wheat Refill', 'amount' => '1800'),
                array('type' => 'Gold Ore Refill', 'amount' => '500'),
                array('type' => 'Blue Flowerbed', 'amount' => '1'),
                array('type' => 'Broken Handcart', 'amount' => '1'),
                array('type' => 'Gold Coin', 'amount' => '150'),
                array('type' => 'Gold Coin', 'amount' => '300')
            ),
            'slot7' => array(

            ),
            'slot8' => array(
                array('type' => 'Return to Bandit Nest', 'amount' => '1'),
                array('type' => 'Nothing', 'amount' => '1')
            ),
        );

        $adventure->create($data);
	}

}