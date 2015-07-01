<?php
namespace LootTracker\Repositories\PriceList;

use Illuminate\Database\Eloquent\Model;

class EloquentPriceListRepository implements PriceListInterface
{
    public function getAllItems()
    {
        $priceItems = PriceListItem::orderBy('Name')->get();
        return $priceItems;
    }

    public function byId($id)
    {
        return PriceListItem::findOrFail($id);
    }
}

