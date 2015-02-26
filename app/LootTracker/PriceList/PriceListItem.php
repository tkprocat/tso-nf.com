<?php
namespace LootTracker\PriceList;

use LootTracker\PriceList\PriceListItemPrice;

class PriceListItem extends \BaseModel {
    protected $table = 'pricelist_item';

    public function price()
    {
        return $this->hasMany('LootTracker\PriceList\PriceListItemPrice', 'pricelist_item_id');
    }

    public function current_price()
    {
        return PriceListItemPrice::where('pricelist_item_id', $this->id)->orderBy('id', 'desc')->firstOrFail();
    }
}