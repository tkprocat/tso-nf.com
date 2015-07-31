<?php namespace LootTracker\Repositories\Item;

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

    /**
     * @param $name
     *
     * @return \LootTracker\Repositories\Item\Item
     */
    public function byName($name)
    {
         try {
            return Item::whereName($name)->firstOrFail();
        } catch(ModelNotFoundException $ex) {
             dd('Missing item: '.$name);
        }
    }
}
