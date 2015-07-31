<?php namespace LootTracker\Repositories\Adventure;

use LootTracker\Repositories\Item\Item;
use LootTracker\Repositories\Item\ItemInterface;

/**
 * Class EloquentAdventureRepository
 * @package LootTracker\Repositories\Adventure
 */
class EloquentAdventureRepository implements AdventureInterface
{

    /**
     * @var ItemInterface
     */
    protected $itemRepo;


    /**
     * EloquentAdventureRepository constructor.
     *
     * @param ItemInterface $itemInterface
     */
    public function __construct(ItemInterface $itemInterface)
    {
        $this->itemRepo = $itemInterface;
    }


    /**
     * @param bool|false $onlyActives
     *
     * @return mixed
     */
    public function all($onlyActives = false)
    {
        if ($onlyActives) {
            $adventures = Adventure::orderBy('Name')->get();
        } else {
            $adventures = Adventure::where('disabled', '0')->orderBy('Name')->get();
        }

        return $adventures;
    }


    /**
     * @param $adventure_id
     *
     * @return mixed
     */
    public function byId($adventure_id)
    {
        return Adventure::findOrFail($adventure_id);
    }


    /**
     * @param $name
     *
     * @return mixed
     */
    public function byName($name)
    {
        return Adventure::whereName($name)->firstOrFail();
    }


    /**
     * @return mixed
     */
    public function findAdventuresWithLoot()
    {
        return Adventure::where('disabled', '0')->orderBy('Name')->with('loot')->get();
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
        $adventure->save();

        for ($slot = 1; $slot < 9; $slot++) {
            if (isset($data['slot' . $slot])) {
                foreach ($data['slot' . $slot] as $item) {
                    $newItem = new AdventureLoot;
                    $newItem->slot = $slot;
                    $newItem->item_id = $item['item_id'];
                    $newItem->amount = $item['amount'];
                    $newItem->adventure_id = $adventure->id;
                    $newItem->save();
                }
            }
        }

        return $adventure;
    }


    /**
     * @param $adventure_id
     *
     * @return mixed
     */
    public function findAllLootForAdventure($adventure_id)
    {
        $adventure = Adventure::findOrFail($adventure_id);

        return $adventure->loot();
    }


    /**
     * @return mixed
     */
    public function findAllDifferentLootTypes()
    {
        return Item::distinct('name')->orderBy('name')->lists('name');
    }


    /**
     * @param $adventure_id
     *
     * @return mixed
     */
    public function findAdventureWithLoot($adventure_id)
    {
        return Adventure::where('id', $adventure_id)->with('loot')->get();
    }
}
