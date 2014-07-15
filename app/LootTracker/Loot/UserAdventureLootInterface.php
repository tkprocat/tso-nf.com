<?php
namespace LootTracker\Loot;

interface UserAdventureLoot {
    public function all();

    public function create($input);

    public function find($id);
}