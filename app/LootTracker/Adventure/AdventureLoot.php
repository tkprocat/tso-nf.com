<?php
namespace LootTracker\Adventure;

class AdventureLoot extends \BaseModel {
    protected $table = 'adventure_loot';
    public static $rules = [
        'type' => 'required',
        'slot' => 'required|min:1|max:8',
        'amount' => 'required|min:0|max:10000'
    ];

    public function scopeSlot($query, $slot)
    {
        return $query->where('slot', '=', $slot);
    }

    public function dropped() {
        return $this->hasMany('\LootTracker\Loot\UserAdventureLoot');
    }

    public function dropCount() {
        return \LootTracker\Loot\UserAdventureLoot::where('adventure_loot_id', '=', $this->id)->count();
    }
}