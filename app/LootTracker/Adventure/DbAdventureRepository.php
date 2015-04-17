<?php
namespace LootTracker\Adventure;

use Illuminate\Database\Eloquent\Model;

class DbAdventureRepository implements AdventureInterface
{
    protected $adventure;

    public function __construct(Model $adventure)
    {
        $this->adventure = $adventure;
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

    public function findAllActiveAdventures()
    {
        // Get all the adventures
        $key = 'ActiveAdventuresOrderByName';
        if (\Cache::has($key)) {
            $adventures = \Cache::get($key);
        } else {
            $adventures = $this->adventure->where('disabled', '0')->orderBy('Name')->get();
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
        return $this->adventure->where('disabled', '0')->orderBy('Name')->with('loot')->get();
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
