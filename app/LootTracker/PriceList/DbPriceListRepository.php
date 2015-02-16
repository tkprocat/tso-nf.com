<?php
namespace LootTracker\PriceList;

use Illuminate\Database\Eloquent\Model;

class DbPriceListRepository implements PriceListInterface
{
    protected $priceItem;

    public function __construct(Model $priceItem)
    {
        $this->priceItem = $priceItem;
    }

    public function getAllItems()
    {
        // Get all the adventures
        $key = 'PriceItems';
        if (\Cache::has($key)) {
            $priceItems = \Cache::get($key);
        } else {
            $priceItems = $this->priceItem->orderBy('Name')->get();
            \Cache::add($key, $priceItems, 1440); // Saves result for a day.
        }
        return $priceItems;
    }

    public function findItemById($id)
    {
        return $this->priceItem->findOrFail($id)->first();
    }
}

