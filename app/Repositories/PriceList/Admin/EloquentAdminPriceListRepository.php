<?php namespace LootTracker\Repositories\PriceList\Admin;

use LootTracker\Repositories\PriceList\PriceListItem;
use LootTracker\Repositories\PriceList\PriceListItemPrice;

class EloquentAdminPriceListRepository implements AdminPriceListInterface
{
    public function all()
    {
        return PriceListItem::orderBy('Name')->get();
    }

    public function byId($item_id)
    {
        $priceChanges = PriceListItem::findOrFail($item_id);

        return $priceChanges;
    }

    public function create($data)
    {
        $item = new PriceListItem();
        $item->name = $data['name'];
        $item->save();

        $price = new PriceListItemPrice();
        $price->pricelist_item_id = $item->id;
        $price->min_price = $data['min_price'];
        $price->avg_price = $data['avg_price'];
        $price->max_price = $data['max_price'];
        $price->save();
    }

    public function delete($item_id)
    {
        $item = PriceListItem::findOrFail($item_id);
        $item->delete();
    }

    public function findAllPriceChangesForItemById($item_id)
    {
        $priceChanges = PriceListItemPrice::where('pricelist_item_id', $item_id)->orderBy('created_at', 'desc')->get();

        return $priceChanges;
    }

    public function update($id, $data)
    {
        $item = PriceListItem::findOrFail($id);
        $item->name = $data['name'];
        $item->save();
    }

    public function updatePriceForItem($item_id, $min_price, $avg_price, $max_price)
    {
        $price = new PriceListItemPrice();
        $price->pricelist_item_id = $item_id;
        $price->min_price = $min_price;
        $price->avg_price = $avg_price;
        $price->max_price = $max_price;
        $price->save();
    }
}
