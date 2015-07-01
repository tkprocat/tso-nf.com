<?php namespace LootTracker\Repositories\PriceList\Admin;

interface AdminPriceListInterface
{
    public function all();

    public function byId($item_id);

    public function create($data);

    public function delete($id);

    public function findAllPriceChangesForItemById($item_id);

    public function update($id, $data);

    public function updatePriceForItem($item_id, $min_price, $avg_price, $max_price);
}