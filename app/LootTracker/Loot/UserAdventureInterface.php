<?php
namespace LootTracker\Loot;


interface UserAdventureInterface {
    public function all();

    public function create($input);

    public function find($id);
}