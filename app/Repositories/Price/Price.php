<?php namespace LootTracker\Repositories\Price;

use Eloquent;
use LootTracker\Repositories\Item\Item;
use LootTracker\Repositories\User\User;

/**
 * LootTracker\Repositories\Price\Price
 *
 * @property integer $id
 * @property integer $item_id
 * @property float $min_price
 * @property float $avg_price
 * @property float $max_price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Item $item
 * @method static \Illuminate\Database\Query\Builder|Price whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Price whereItemsId($value)
 * @method static \Illuminate\Database\Query\Builder|Price whereMinPrice($value)
 * @method static \Illuminate\Database\Query\Builder|Price whereAvgPrice($value)
 * @method static \Illuminate\Database\Query\Builder|Price whereMaxPrice($value)
 * @method static \Illuminate\Database\Query\Builder|Price whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Price whereUpdatedAt($value)
 */
class Price extends Eloquent
{
    protected $table = 'prices';

    /**
     * @return Item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
