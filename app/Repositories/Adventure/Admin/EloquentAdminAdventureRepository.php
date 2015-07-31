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
     * @param $adventureId
     *
     * @return mixed
     */
    public function findAdventureById($adventureId)
    {
        return Adventure::findOrFail($adventureId);
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
            // Small hack to check the item is set since itemid always will be filled out.
            if ((isset($item['slot'])) && ($item['slot'] != '') && (isset( $item['amount'])) && ($item['amount'] != '')) {
                    $newItem = new AdventureLoot;
                    $newItem->slot = $item['slot'];
                    $newItem->item_id = $item['itemid'];
                    $newItem->amount = $item['amount'];
                    $newItem->adventure_id = $adventure->id;
                    $newItem->save();
            }
        }
        return $adventure;
    }


    /**
     * @param $adventureId
     * @param $data
     *
     * @return mixed
     */
    public function update($adventureId, $data)
    {
        $adventure = Adventure::findOrFail($adventureId);
        $adventure->name = $data['name'];
        $adventure->type = (isset($data['type']) ? $data['type'] : '');
        $adventure->disabled = (isset($data['disabled']) ? ($data['disabled'] == 'on') : '0');
        $adventure->save();

        foreach ($data['items'] as $item) {
            // Small hack to check the item is set since itemid always will be filled out.
            if ((isset($item['slot'])) && ($item['slot'] != '') && (isset( $item['amount'])) && ($item['amount'] != '')) {
                if (isset( $item['id'] )) { //Update
                    $updateItem               = AdventureLoot::find($item['id']);
                    $updateItem->slot         = $item['slot'];
                    $updateItem->item_id      = $item['itemid'];
                    $updateItem->amount       = $item['amount'];
                    $updateItem->adventure_id = $adventure->id;
                    $updateItem->save();
                } else { // Add item
                    $newItem               = new AdventureLoot;
                    $newItem->slot         = $item['slot'];
                    $newItem->item_id      = $item['itemid'];
                    $newItem->amount       = $item['amount'];
                    $newItem->adventure_id = $adventure->id;
                    $newItem->save();
                }
            }
        }

        return $adventure;
    }


    /**
     * @param $adventureId
     *
     * @return mixed
     */
    public function findAllLootForAdventure($adventureId)
    {
        return Adventure::firstOrFail($adventureId)->loot();
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
