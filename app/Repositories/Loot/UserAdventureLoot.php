<?php namespace LootTracker\Repositories\Loot;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAdventureLoot
 * @package LootTracker\Repositories\Loot
 */
class UserAdventureLoot extends Model
{

    /**
     * @var array
     */
    public static $rules = [
        'user_adventure_id' => 'required',
        'adventure_id' => 'required|exists:adventure_loot,id'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'user_adventure_loot';

    /**
     * @var string
     */
    protected $primaryKey = 'id';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userAdventure()
    {
        return $this->belongsTo('LootTracker\Repositories\Loot\UserAdvenure');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loot()
    {
        return $this->belongsTo('LootTracker\Repositories\Adventure\AdventureLoot', 'adventure_loot_id');
    }


    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeName($query)
    {
        return $query->orderBy('slot', 'asc');
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->ID;
    }


    /**
     * @param $query
     * @param $slot
     *
     * @return mixed
     */
    public function scopeSlot($query, $slot)
    {
        return $query->join('adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->join('user_adventure', 'user_adventure.adventure_id', '=', 'adventure_loot.adventure_id')
            ->where('adventure_loot.slot', $slot);
    }
}
