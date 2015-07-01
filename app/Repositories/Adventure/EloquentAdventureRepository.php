<?php namespace LootTracker\Repositories\Adventure;

class EloquentAdventureRepository implements AdventureInterface
{
    public function all($onlyActives = false)
    {
        if ($onlyActives) {
            $adventures = Adventure::orderBy('Name')->get();
        } else {
            $adventures = Adventure::where('disabled', '0')->orderBy('Name')->get();
        }

        return $adventures;
    }

    public function byId($adventure_id)
    {
        return Adventure::findOrFail($adventure_id);
    }

    public function byName($name)
    {
        return Adventure::whereName($name)->firstOrFail();
    }

    public function findAdventuresWithLoot()
    {
        return Adventure::where('disabled', '0')->orderBy('Name')->with('loot')->get();
    }


    public function create($data)
    {
        $adventure = new Adventure();
        $adventure->name = $data['name'];
        $adventure->save();

        for ($slot = 1; $slot < 9; $slot++) {
            if (!is_null($data['slot' . $slot])) {
                foreach ($data['slot' . $slot] as $item) {
                    $newItem = new AdventureLoot;
                    $newItem->slot = $slot;
                    $newItem->type = $item['type'];
                    $newItem->amount = $item['amount'];
                    $newItem->adventure_id = $adventure->id;
                    $newItem->save();
                }
            }
        }

        return $adventure;
    }

    public function findAllLootForAdventure($adventure_id)
    {
        $adventure = Adventure::findOrFail($adventure_id);

        return $adventure->loot();
    }

    public function findAllDifferentLootTypes()
    {
        return AdventureLoot::distinct('type')->orderBy('type')->lists('type');
    }

    public function findAdventureWithLoot($adventure_id)
    {
        return Adventure::where('id', $adventure_id)->with('loot')->get();
    }
}
