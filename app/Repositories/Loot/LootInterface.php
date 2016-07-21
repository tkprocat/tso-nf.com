<?php namespace LootTracker\Repositories\Loot;

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

    public function getLootByNameAndAmount($adventure_id, $slot, $item_name, $amount);

    public function getAllUserAdventuresForUserWithLoot($user_id, $from, $to);

    public function create($data);

    public function delete($id);

    public function paginate($itemsPerPage, $page, $adventure_name = '', $user_id = 0);

    public function update($data);
}
