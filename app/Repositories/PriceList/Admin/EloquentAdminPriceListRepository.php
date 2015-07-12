<?php namespace LootTracker\Repositories\PriceList\Admin;

use LootTracker\Repositories\PriceList\PriceListItem;
use LootTracker\Repositories\PriceList\PriceListItemPrice;

/**
 * Class EloquentAdminPriceListRepository
 * @package LootTracker\Repositories\PriceList\Admin
 */
class EloquentAdminPriceListRepository implements AdminPriceListInterface
{

    /**
     * @return mixed
     */
    public function all()
    {
        return PriceListItem::orderBy('Name')->get();
    }


    /**
     * @param $item_id
     *
     * @return mixed
     */
    public function byId($item_id)
    {
        $priceChanges = PriceListItem::findOrFail($item_id);

        return $priceChanges;
    }


    /**
     * @param $data
     */
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


    /**
     * @param $item_id
     */
    public function delete($item_id)
    {
        $item = PriceListItem::findOrFail($item_id);
        $item->delete();
    }


    /**
     * @param $item_id
     *
     * @return mixed
     */
    public function findAllPriceChangesForItemById($item_id)
    {
        $priceChanges = PriceListItemPrice::where('pricelist_item_id', $item_id)->orderBy('created_at', 'desc')->get();

        return $priceChanges;
    }


    /**
     * @param $id
     * @param $data
     */
    public function update($id, $data)
    {
        $item = PriceListItem::findOrFail($id);
        $item->name = $data['name'];
        $item->save();
    }


    /**
     * @param $item_id
     * @param $min_price
     * @param $avg_price
     * @param $max_price
     */
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
