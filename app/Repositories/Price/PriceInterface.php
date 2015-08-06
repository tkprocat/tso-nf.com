<?php namespace LootTracker\Repositories\Price;

interface PriceInterface
{
    public function findAllPriceChangesForItemById($item_id);
}
