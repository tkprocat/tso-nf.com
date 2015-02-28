<?php
namespace LootTracker\PriceList\Admin;

use LootTracker\PriceList\PriceListItem;
use LootTracker\PriceList\PriceListItemPrice;
use Illuminate\Database\Eloquent\Model;

class DbAdminPriceListRepository implements AdminPriceListInterface
{
    protected $priceList;
    public $validator;

    public function __construct(Model $priceList, AdminPriceListFormValidator $validator)
    {
        $this->priceList = $priceList;
        $this->validator = $validator;
    }

    public function getAllItems()
    {
        // Get all the adventures
        $key = 'Prices';
        if (\Cache::has($key)) {
            $prices = \Cache::get($key);
        } else {
            $prices = $this->priceList->orderBy('Name')->get();
            \Cache::add($key, $prices, 1440); // Saves result for a day.
        }
        return $prices;
    }

    public function getAllPriceChangesForItemById($item_id)
    {
        $priceChanges = PriceListItemPrice::where('pricelist_item_id', $item_id)->orderBy('created_at desc')->get();
        return $priceChanges;
    }

    public function addItem($data)
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