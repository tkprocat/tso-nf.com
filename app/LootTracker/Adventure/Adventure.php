<?php
namespace LootTracker\Adventure;

class Adventure extends \BaseModel {
    protected $table = 'adventure';

    public function loot() {
        return $this->hasMany('\LootTracker\Adventure\AdventureLoot')->orderBy('slot')->orderBy('type')->orderBy('amount');
    }

    public function played() {
        return $this->hasMany('\LootTracker\Loot\UserAdventure');
    }
}