<?php
namespace LootTracker\Adventure;

interface AdventureLootInterface {
    public function all();

    public function create($input);

    public function find($id);
}