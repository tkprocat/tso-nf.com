<?php
namespace LootTracker\Repositories\Adventure;

use Illuminate\Database\Eloquent\Model;
use LootTracker\Repositories\Loot\UserAdventureLoot;

class AdventureLoot extends Model
{
    protected $table = 'adventure_loot';
    public static $rules = [
        'type' => 'required',
        'slot' => 'required|min:1|max:8',
        'amount' => 'required|min:0|max:10000'
    ];


    public function adventure()
    {
        return $this->belongsTo('LootTracker\Repositories\Adventure\Adventure');
    }

    public function scopeSlot($query, $slot)
    {
        return $query->where('slot', '=', $slot)->get();
    }

    public function dropped()
    {
        return $this->hasMany('LootTracker\Repositories\Loot\UserAdventureLoot');
    }

    public function dropCount()
    {
        return UserAdventureLoot::where('adventure_loot_id', '=', $this->id)->count();
    }
}