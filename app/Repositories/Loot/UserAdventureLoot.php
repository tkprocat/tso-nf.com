<?php namespace LootTracker\Repositories\Loot;

use Illuminate\Database\Eloquent\Model;

class UserAdventureLoot extends Model
{
    public static $rules = [
        'user_adventure_id' => 'required',
        'adventure_id' => 'required|exists:adventure_loot,id'
    ];
    public $timestamps = false;
    protected $table = 'user_adventure_loot';
    protected $primaryKey = 'id';

    public function userAdventure()
    {
        return $this->belongsTo('LootTracker\Repositories\Loot\UserAdvenure');
    }

    public function loot()
    {
        return $this->belongsTo('LootTracker\Repositories\Adventure\AdventureLoot', 'adventure_loot_id');
    }

    public function scopeName($query)
    {
        return $query->orderBy('slot', 'asc');
    }

    public function getId()
    {
        return $this->ID;
    }

    public function scopeSlot($query, $slot)
    {
        return $query->join('adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->join('user_adventure', 'user_adventure.adventure_id', '=', 'adventure_loot.adventure_id')
            ->where('adventure_loot.slot', $slot);
    }
}