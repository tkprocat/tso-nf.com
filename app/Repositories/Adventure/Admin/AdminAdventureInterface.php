<?php
namespace LootTracker\Repositories\Adventure\Admin;


interface AdminAdventureInterface
{
    public function all();

    public function create($data);

    public function findAdventureById($id);

    public function findAdventureByName($name);

    public function getAdventuresWithLoot();

    public function findAllLootForAdventure($id);

    public function findAllDifferentLootTypes();

    public function getItemTypes();

    public function getAdventureTypes();
}