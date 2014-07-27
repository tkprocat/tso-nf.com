<?php
namespace LootTracker\Adventure;


interface AdventureInterface {
    public function findAllAdventures();

    public function create($data);

    public function findAdventureById($id);

    public function findAdventureByName($name);

    public function getAdventuresWithLoot();

    public function findAllLootForAdventure($id);
}