<?php
namespace LootTracker\PriceList\Admin;

use LootTracker\PriceList\PriceItem;
use LootTracker\PriceList\PriceItemPrice;
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

    public function findAllPrices()
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

    public function create($data)
    {
        $item = new PriceItem();
        $item->name = $data['name'];
        $item->save();

        $price = new PriceItemPrice();
        $price->price_item_id = $item->id;
        $price->min_price = $data['min_price'];
        $price->avg_price = $data['avg_price'];
        $price->max_price = $data['max_price'];
        $price->save();
    }

}
