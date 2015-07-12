<?php
namespace LootTracker\Repositories\PriceList;

use Illuminate\Database\Eloquent\Model;

class PriceListItem extends Model
{
    protected $table = 'pricelist_item';

    public function price()
    {
        return $this->hasMany('LootTracker\Repositories\PriceList\PriceListItemPrice', 'pricelist_item_id');
    }

    public function currentPrice()
    {
        return PriceListItemPrice::where('pricelist_item_id', $this->id)->orderBy('id', 'desc')->firstOrFail();
    }
}