<?php
namespace LootTracker\PriceList\Admin;

use LootTracker\PriceList\PriceListItem;
use LootTracker\PriceList\PriceListItemPrice;
use Illuminate\Database\Eloquent\Model;

class DbAdminPriceListRepository implements AdminPriceListInterface
{
    protected $priceList;
    public $validator;
    public $validatorNewPrice;

    public function __construct(Model $priceList, AdminPriceItemFormValidator $validator, AdminPriceItemNewPriceFormValidator $validatorNewPrice)
    {
        $this->priceList = $priceList;
        $this->validator = $validator;
        $this->validatorNewPrice = $validatorNewPrice;
    }

    public function getAllItems()
    {
        // Get all the adventures
        $key = 'all_items';
        if (\Cache::tags('pricelist_item')->has($key)) {
            $prices = \Cache::tags('pricelist_item')->get($key);
        } else {
            $prices = $this->priceList->orderBy('Name')->get();
            \Cache::tags('pricelist_item')->add($key, $prices, 1440); // Saves result for a day.
        }
        return $prices;
    }

    public function findPriceById($item_id)
    {
        $priceChanges = PriceListItem::findOrFail($item_id);
        return $priceChanges;
    }

    public function getAllPriceChangesForItemById($item_id)
    {
        $priceChanges = PriceListItemPrice::where('pricelist_item_id', $item_id)->orderBy('created_at', 'desc')->get();
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

    public function updateItem($id, $data) {
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

    public function deleteItem($item_id)
    {
        $item = PriceListItem::findOrFail($item_id);
        $item->delete();
    }
}
