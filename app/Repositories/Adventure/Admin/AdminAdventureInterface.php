<?php namespace LootTracker\Repositories\Adventure\Admin;

interface AdminAdventureInterface
{
    public function all();

    public function create($data);

    public function findAdventureById($adventureId);

    public function findAdventureByName($name);

    public function getAdventuresWithLoot();

    public function findAllLootForAdventure($adventureId);
}
