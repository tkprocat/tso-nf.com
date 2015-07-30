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
            'name' => 'Coal',
            'category' => 'Resource',
            'min_price' => '0.00001',
            'avg_price' => '0.00002',
            'max_price' => '0.00003',
            'user_id' => '2'
        ]);
    }
}