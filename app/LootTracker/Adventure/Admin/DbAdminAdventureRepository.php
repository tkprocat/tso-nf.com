<?php
namespace LootTracker\Adventure\Admin;

use LootTracker\Adventure\Adventure;
use LootTracker\Adventure\AdventureLoot;
use Illuminate\Database\Eloquent\Model;
use LootTracker\Loot\UserAdventure;

class DbAdminAdventureRepository implements AdminAdventureInterface
{
    protected $adventure;
    public $validator;

    public function __construct(Model $adventure, AdminAdventureFormValidator $validator)
    {
        $this->adventure = $adventure;
        $this->validator = $validator;
    }

    public function findAllAdventures()
    {
        // Get all the adventures
        $key = 'AdventuresOrderByName';
        if (\Cache::has($key)) {
            $adventures = \Cache::get($key);
        } else {
            $adventures = $this->adventure->orderBy('Name')->get();
            \Cache::add($key, $adventures, 1440); // Saves result for a day.
        }
        return $adventures;
    }

    public function findAdventureById($id)
    {
        return $this->adventure->findOrFail($id);
    }

    public function getAdventuresWithLoot()
    {
        return $this->adventure->orderBy('Name')->with('loot')->get();
    }

    public function create($data)
    {
        $adventure = new Adventure();
        $adventure->name = $data['name'];
        $adventure->type = (isset($data['type']) ? $data['type'] : '');
        $adventure->disabled = (isset($data['disabled']) ? ($data['disabled'] == 'on') : '0');
        $adventure->save();

        foreach($data['items'] as $item) {
            $newItem = new AdventureLoot;
            $newItem->slot = $item['slot'];
            $newItem->type = $item['type'];
            $newItem->amount = $item['amount'];
            $newItem->adventure_id = $adventure->id;
            $newItem->save();
        }

        //Remove the cache object and reload it on next use.
        \Cache::forget('AdventuresOrderByName');
        return $adventure;
    }

    public function update($id, $data)
    {
        $adventure = Adventure::findOrFail($id);
        $adventure->name = $data['name'];
        $adventure->type = (isset($data['type']) ? $data['type'] : '');
        $adventure->disabled = (isset($data['disabled']) ? ($data['disabled'] == 'on') : '0');
        $adventure->save();

        foreach($data['items'] as $item) {

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

        //Remove the cache object and reload it on next use.
        \Cache::forget('AdventuresOrderByName');
        return $adventure;
    }

    public function findAllLootForAdventure($id)
    {
        return $this->adventure->loot();
    }

    public function findAdventureByName($name)
    {
        return $this->adventure->whereName($name)->first();
    }

    public function findAllDifferentLootTypes()
    {
        return AdventureLoot::distinct('type')->orderBy('type')->lists('type');
    }
}
