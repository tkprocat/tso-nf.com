<?php
namespace LootTracker\PriceList\Admin;

interface AdminPriceListInterface {
    public function getAllItems();
    public function getAllPriceChangesForItemById($item_id);
    public function addItem($data);
    public function updatePriceForItem($item_id, $min_price, $avg_price, $max_price);
}