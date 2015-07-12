<?php namespace LootTracker\Repositories\PriceList;

/**
 * Class EloquentPriceListRepository
 * @package LootTracker\Repositories\PriceList
 */
class EloquentPriceListRepository implements PriceListInterface
{

    /**
     * @return mixed
     */
    public function getAllItems()
    {
        $priceItems = PriceListItem::orderBy('Name')->get();
        return $priceItems;
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function byId($id)
    {
        return PriceListItem::findOrFail($id);
    }
}
