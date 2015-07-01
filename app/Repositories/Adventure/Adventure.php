<?php namespace LootTracker\Repositories\Adventure;

use Illuminate\Database\Eloquent\Model;

class Adventure extends Model
{
    protected $table = 'adventure';

    public function loot()
    {
        return $this->hasMany('\LootTracker\Repositories\Adventure\AdventureLoot')->orderBy('slot')->orderBy('type')->orderBy('amount');
    }

    public function played()
    {
        return $this->hasMany('\LootTracker\Repositories\Loot\UserAdventure');
    }

    public function getTypeAndNameAttribute()
    {
        return trim($this->type . ' - ' . $this->name);
    }
}