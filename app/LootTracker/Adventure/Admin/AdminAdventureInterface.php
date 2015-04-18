<?php
namespace LootTracker\Adventure\Admin;


interface AdminAdventureInterface {
    public function findAllAdventures();

    public function create($data);

    public function findAdventureById($id);

    public function findAdventureByName($name);

    public function getAdventuresWithLoot();

    public function findAllLootForAdventure($id);

    public function findAllDifferentLootTypes();

    public function getSubmissionsForWeek($date);
}