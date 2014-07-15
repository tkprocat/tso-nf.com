<?php
namespace LootTracker\Loot;

interface LootInterface {
    public function all();

    public function create($data);

    public function update($data);

    public function findPage($page, $lootPerPage);

    public function findUserAdventureById($id);

    public function findAllAdventuresForUser($id);

    public function findAllLootForUserAdventure($id);

    public function findAllPlayedAdventures();

    public function findLootCountForAllPlayedAdventures();

    public function getAllAdventuresWithPlayedAndLoot();

    public function getAdventuresForUserWithPlayed($user_id, $from, $to);

    public function getAllUserAdventuresForUserWithLoot($user_id, $from, $to);

    public function getAllUserAdventuresWithLoot();

    public function getLootDropCount();

    public function getLootDropCountForUser($user_id, $from, $to);
}