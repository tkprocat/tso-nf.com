<?php namespace LootTracker\Repositories\Item;

use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;
use SebastianBergmann\Environment\Console;

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
        $items = Item::orderBy('Name')->get();
        return $items;
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

    /**
     * @param $name
     *
     * @return \LootTracker\Repositories\Item\Item
     */
    public function byName($name)
    {
        return Item::whereName($name)->firstOrFail();
    }


    public function getItemsWithPrices()
    {
        $items = Item::with('currentPrice')->select('id', 'name', 'category', 'tradable', 'stackable')->orderBy('name')->get();
        return $items;
    }
}
