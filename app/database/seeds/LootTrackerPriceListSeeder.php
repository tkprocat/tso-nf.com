<?php

class LootTrackerPriceListSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        $priceList = App::make('LootTracker\PriceList\Admin\AdminPriceListInterface');
        $data = array(
            'name' => 'coal',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003'
        );

        $priceList->addItem($data);
	}
}