<?php
namespace LootTracker\Repositories\Loot;

interface LootInterface
{
    public function all();

    public function byId($id);

    public function findAllAdventuresForUser($id);

    public function findAllLootForUserAdventure($id);

    public function findAllPlayedAdventures();

    public function getAllAdventuresWithPlayedAndLoot();

    public function getAdventureWithPlayedAndLoot($adventure_id);

    public function getAllUserAdventuresWithLoot();

    public function getLootDropCount();

    public function getLootDropCountForAdventure($adventure_id);

    public function getAllUserAdventuresForUserWithLoot($user_id, $from, $to);

    public function create($data);

    public function paginate($itemsPerPage, $adventure_name = '', $user_id = 0);

    public function update($data);
}