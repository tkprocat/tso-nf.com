<?php
namespace LootTracker\PriceList;

use LootTracker\PriceList\PriceItemPrice;

class PriceItem extends \BaseModel {
    protected $table = 'price_item';

    public function price()
    {
        return $this->hasMany('LootTracker\PriceList\PriceItemPrice');
    }
}