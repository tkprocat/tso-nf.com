<?php namespace LootTracker\Repositories\Adventure\Admin;

use DB;
use LootTracker\Repositories\Adventure\Adventure;
use LootTracker\Repositories\Adventure\AdventureLoot;

/**
 * Class EloquentAdminAdventureRepository
 * @package LootTracker\Repositories\Adventure\Admin
 */
class EloquentAdminAdventureRepository implements AdminAdventureInterface
{

    /**
     * @return mixed
     */
    public function all()
    {
        return Adventure::orderBy('Name')->get();
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function findAdventureById($id)
    {
        return Adventure::findOrFail($id);
    }


    /**
     * @return mixed
     */
    public function getAdventuresWithLoot()
    {
        return Adventure::orderBy('Name')->with('loot')->get();
    }


    /**
     * @param $data
     *
     * @return Adventure
     */
    public function create($data)
    {
        $adventure = new Adventure();
        $adventure->name = $data['name'];
        $adventure->type = (isset($data['type']) ? $data['type'] : '');
        $adventure->disabled = (isset($data['disabled']) ? ($data['disabled'] == 'on') : '0');
        $adventure->save();

        foreach ($data['items'] as $item) {
            $newItem = new AdventureLoot;
            $newItem->slot = $item['slot'];
            $newItem->type = $item['type'];
            $newItem->amount = $item['amount'];
            $newItem->adventure_id = $adventure->id;
            $newItem->save();
        }

        return $adventure;
    }


    /**
     * @param $id
     * @param $data
     *
     * @return mixed
     */
    public function update($id, $data)
    {
        $adventure = Adventure::findOrFail($id);
        $adventure->name = $data['name'];
        $adventure->type = (isset($data['type']) ? $data['type'] : '');
        $adventure->disabled = (isset($data['disabled']) ? ($data['disabled'] == 'on') : '0');
        $adventure->save();

        foreach ($data['items'] as $item) {
            if (isset($item['id'])) { //Update
                $updateItem = AdventureLoot::find($item['id']);
                $updateItem->slot = $item['slot'];
                $updateItem->type = $item['type'];
                $updateItem->amount = $item['amount'];
                $updateItem->adventure_id = $adventure->id;
                $updateItem->save();
            } else {
                $newItem = new AdventureLoot;
                $newItem->slot = $item['slot'];
                $newItem->type = $item['type'];
                $newItem->amount = $item['amount'];
                $newItem->adventure_id = $adventure->id;
                $newItem->save();
            }
        }

        return $adventure;
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function findAllLootForAdventure($id)
    {
        return Adventure::firstOrFail($id)->loot();
    }


    /**
     * @param $name
     *
     * @return mixed
     */
    public function findAdventureByName($name)
    {
        return Adventure::whereName($name)->first();
    }


    /**
     * @return mixed
     */
    public function findAllDifferentLootTypes()
    {
        //TODO: either remove this or getItemTypes().
        return AdventureLoot::distinct('type')->orderBy('type')->lists('type');
    }


    /**
     * @return array
     */
    public function getItemTypes()
    {
        return DB::table('adventure_loot')->groupBy('type')->orderBy('type')->lists('type');
    }


    /**
     * @return array
     */
    public function getAdventureTypes()
    {
        return DB::table('adventure')->groupBy('type')->orderBy('type')->lists('type');
    }
}
