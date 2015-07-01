<?php namespace LootTracker\Repositories\Adventure;

interface AdventureInterface
{
    public function all($activeOnly = false);

    public function create($data);

    public function byId($adventure_id);

    public function byName($name);

    public function findAdventureWithLoot($adventure_id);

    public function findAdventuresWithLoot();

    public function findAllLootForAdventure($adventure_id);

    public function findAllDifferentLootTypes();
}