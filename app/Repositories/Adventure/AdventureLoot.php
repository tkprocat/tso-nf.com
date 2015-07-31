<?php namespace LootTracker\Repositories\Adventure;

use Illuminate\Database\Eloquent\Model;
use LootTracker\Repositories\Item\Item;
use LootTracker\Repositories\Loot\UserAdventureLoot;

/**
 * Class AdventureLoot
 * @package LootTracker\Repositories\Adventure
 */
class AdventureLoot extends Model
{

    /**
     * @var string
     */
    protected $table = 'adventure_loot';

    /**
     * @var array
     */
    public static $rules = [
        'type' => 'required',
        'slot' => 'required|min:1|max:8',
        'amount' => 'required|min:0|max:10000'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adventure()
    {
        return $this->belongsTo('LootTracker\Repositories\Adventure\Adventure');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }


    /**
     * @param $query
     * @param $slot
     *
     * @return mixed
     */
    public function scopeSlot($query, $slot)
    {
        return $query->where('slot', '=', $slot)->get();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dropped()
    {
        return $this->hasMany('LootTracker\Repositories\Loot\UserAdventureLoot');
    }


    /**
     * @return mixed
     */
    public function dropCount()
    {
        return UserAdventureLoot::where('adventure_loot_id', '=', $this->id)->count();
    }
}