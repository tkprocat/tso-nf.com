<?php namespace LootTracker\Repositories\Adventure;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Adventure
 * @package LootTracker\Repositories\Adventure
 */
class Adventure extends Model
{

    /**
     * @var string
     */
    protected $table = 'adventure';


    /**
     * @return mixed
     */
    public function loot()
    {
        return $this->hasMany('\LootTracker\Repositories\Adventure\AdventureLoot')
            ->join('items', 'items.id', '=', 'adventure_loot.item_id')
            ->select(array('adventure_loot.id','slot','name','amount','item_id'))
            ->orderBy('slot')
            ->orderBy('name')
            ->orderBy('amount');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function played()
    {
        return $this->hasMany('\LootTracker\Repositories\Loot\UserAdventure');
    }


    /**
     * @return string
     */
    public function getTypeAndNameAttribute()
    {
        return trim($this->type . ' - ' . $this->name);
    }
}
