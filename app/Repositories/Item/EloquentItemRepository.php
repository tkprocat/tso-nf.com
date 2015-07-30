<?php namespace LootTracker\Repositories\Item;

/**
 * Class EloquentItemRepository
 * @package LootTracker\Repositories\Item
 */
class EloquentItemRepository implements ItemInterface
{

    /**
     * @return mixed
     */
    public function all()
    {
        $priceItems = Item::orderBy('Name')->get();
        return $priceItems;
    }


    /**
     * @param $id
     *
     * @return \LootTracker\Repositories\Item\Item
     */
    public function byId($id)
    {
        return Item::findOrFail($id);
    }
}
