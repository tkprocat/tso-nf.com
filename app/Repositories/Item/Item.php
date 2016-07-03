<?php namespace LootTracker\Repositories\Item;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use LootTracker\Repositories\Price\Price;

/**
 * LootTracker\Repositories\Item\Item
 *
 * @property integer $id
 * @property string $name
 * @property string $category
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Price[] $price
 * @method static \Illuminate\Database\Query\Builder|Item whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Item whereName($value)
 * @method static \Illuminate\Database\Query\Builder|Item whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Item whereUpdatedAt($value)
 */
class Item extends Eloquent
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'items';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function price()
    {
        return $this->hasMany(Price::class);
    }


    /**
     * @return mixed
     */
    public function currentPrice()
    {
        return $this->hasOne(Price::class)->latest()->select('item_id', 'min_price', 'avg_price', 'max_price')->orderBy('ID', 'desc');
    }
}