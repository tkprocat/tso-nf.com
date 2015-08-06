<?php namespace LootTracker\Repositories\Price;

/**
 * Class EloquentPriceRepository
 * @package LootTracker\Repositories\Price
 */
class EloquentPriceRepository implements PriceInterface
{
    /**
     * Returns all price changes for a given item.
     *
     * @param $item_id
     *
     * @return mixed
     */
    public function findAllPriceChangesForItemById($item_id)
    {
        $priceChanges = Price::where('item_id', $item_id)->orderBy('created_at', 'desc')->get();

        return $priceChanges;
    }
}
