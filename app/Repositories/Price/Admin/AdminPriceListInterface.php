<?php namespace LootTracker\Repositories\Price\Admin;

interface AdminPriceInterface
{
    public function findAllPriceChangesForItemById($item_id);

    public function update($item_id, $min_price, $avg_price, $max_price, $user_id);
}
