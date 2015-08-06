<?php namespace LootTracker\Repositories\Price\Admin;

use LootTracker\Repositories\Price\Price;

/**
 * Class EloquentAdminPriceRepository
 * @package LootTracker\Repositories\Price\Admin
 */
class EloquentAdminPriceRepository implements AdminPriceInterface
{
    /**
     * @param $item_id
     * @param $min_price
     * @param $avg_price
     * @param $max_price
     */
    public function update($item_id, $min_price, $avg_price, $max_price, $user_id)
    {
        $price = new Price();
        $price->item_id = $item_id;
        $price->min_price = $min_price;
        $price->avg_price = $avg_price;
        $price->max_price = $max_price;
        $price->user_id = $user_id;
        $price->save();
    }
}
