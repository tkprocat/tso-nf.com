<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use LootTracker\Repositories\Item\Admin\AdminItemInterface;

class ItemSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item = App::make(AdminItemInterface::class);
        $item->create([
            'name'      => 'Coal',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Exotic Wood Log',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Granite',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Titanium Ore',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Hardwood Plank',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Marble',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Iron Sword',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Horse',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Longbow',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Steel Sword',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Brew',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Bread',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Sausage',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Settler',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Wheat Refill',
            'category'  => 'Buff',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Gold Ore Refill',
            'category'  => 'Buff',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Blue Flowerbed',
            'category'  => 'Decoration',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Broken Handcart',
            'category'  => 'Decoration',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Gold Coin',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Return to Bandit Nest',
            'category'  => 'Adventure',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Nothing',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Saltpeter',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Cannon',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Crossbow',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Damascene Sword',
            'category'  => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Angel Monument',
            'category'  => 'Decoration',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Dark Castle',
            'category'  => 'Decoration',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Copper Ore',
            'category'  => 'Resource',
            'min_price' => '0.35',
            'avg_price' => '0.43',
            'max_price' => '0.50',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Iron Ore',
            'category'  => 'Resource',
            'min_price' => '0.170',
            'avg_price' => '0.185',
            'max_price' => '0.190',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Pinewood Log',
            'category'  => 'Resource',
            'min_price' => '0.10',
            'avg_price' => '0.13',
            'max_price' => '0.15',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Hardwood Log',
            'category'  => 'Resource',
            'min_price' => '0.20',
            'avg_price' => '0.30',
            'max_price' => '0.40',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Meat',
            'category'  => 'Resource',
            'min_price' => '0.14',
            'avg_price' => '0.15',
            'max_price' => '0.16',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Coal Deposit Refill',
            'category'  => 'Refill',
            'min_price' => '0.125',
            'avg_price' => '0.15',
            'max_price' => '0.175',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Copper Deposit Refill',
            'category'  => 'Refill',
            'min_price' => '0.125',
            'avg_price' => '0.15',
            'max_price' => '0.180',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Gold Deposit Refill',
            'category'  => 'Refill',
            'min_price' => '0',
            'avg_price' => '0',
            'max_price' => '0',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Iron Deposit Refill',
            'category'  => 'Refill',
            'min_price' => '0',
            'avg_price' => '0',
            'max_price' => '0',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Meat Deposit Refill',
            'category'  => 'Refill',
            'min_price' => '1.4',
            'avg_price' => '1.5',
            'max_price' => '1.6',
            'user_id'   => '2'
        ]);

        $item->create([
            'name'      => 'Return to the Bandit Nest',
            'category'  => 'Adventure',
            'min_price' => '10000',
            'avg_price' => '11000',
            'max_price' => '12000',
            'user_id'   => '2'
        ]);
    }
}