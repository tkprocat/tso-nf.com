<?php namespace LootTracker\Repositories\Loot;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use LootTracker\Repositories\Adventure\Adventure;
use LootTracker\Repositories\Adventure\AdventureLoot;
use LootTracker\Repositories\Item\Item;

/**
 * Class EloquentLootRepository
 * @package LootTracker\Repositories\Loot
 */
class EloquentLootRepository implements LootInterface
{

    /**
     * @return mixed
     */
    public function all()
    {
        return UserAdventure::all();
    }


    /**
     * @param int    $itemsPerPage
     * @param string $adventure_name
     * @param int    $user_id
     *
     * @return mixed
     */
    public function paginate($itemsPerPage, $page, $adventure_name = '', $user_id = 0)
    {
        if ($adventure_name != '') {
            $adventureRepo = App::make('LootTracker\Repositories\Adventure\AdventureInterface');
            $adventure     = $adventureRepo->byName(urldecode($adventure_name));
            //Check if we have the adventure
            if ($user_id > 0) {
                return Cache::tags('loot')->remember('all_loots_adventure_'.$adventure_name.'_user_'.$user_id.'_page_'.$page, 5, function() use ($itemsPerPage, $user_id, $adventure) {
                    return UserAdventure::with(
                        'loot',
                        'loot.loot',
                        'loot.loot.item',
                        'loot.loot.item.currentPrice',
                        'user',
                        'adventure',
                        'user.guild'
                    )->where('adventure_id', $adventure->id)->
                        where('user_id', $user_id)->
                        orderBy('created_at', 'desc')->paginate($itemsPerPage);
                });
            } else {
                return Cache::tags('loot')->remember('all_loots_adventure_'.$adventure_name.'_page_'.$page, 5, function() use ($itemsPerPage, $user_id, $adventure) {
                    return UserAdventure::with(
                        'loot',
                        'loot.loot',
                        'loot.loot.item',
                        'loot.loot.item.currentPrice',
                        'user',
                        'adventure',
                        'user.guild'
                    )->where('adventure_id', $adventure->id)->
                        orderBy('created_at', 'desc')->paginate($itemsPerPage);
                });
            } //Return all if we can't find it.
        } elseif ($user_id > 0) {
            return Cache::tags('loot')->remember('all_loots_userid_'.$user_id.'_page_'.$page, 5, function() use ($itemsPerPage, $user_id) {
                return UserAdventure::with(
                    'loot',
                    'loot.loot',
                    'loot.loot.item',
                    'loot.loot.item.currentPrice',
                    'user',
                    'adventure',
                    'user.guild'
                )->whereUserId($user_id)->orderBy(
                    'created_at',
                    'desc'
                )->paginate($itemsPerPage);
            });
        } else {
            return Cache::tags('loot')->remember('all_loots_page_'.$page, 5, function() use ($itemsPerPage) {
                return UserAdventure::with(
                    'loot',
                    'loot.loot',
                    'loot.loot.item',
                    'loot.loot.item.currentPrice',
                    'user',
                    'adventure',
                    'user.guild'
                )->orderBy('created_at', 'desc')->paginate($itemsPerPage);
            });
        }
    }


    /**
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model|static
     */
    public function byId($id)
    {
        return UserAdventure::with('loot', 'loot.loot.item', 'loot.loot.item.currentPrice')->findOrFail($id);
    }


    /**
     * @param $data
     *
     * @return UserAdventure
     */
    public function create($data)
    {
        $adventure_id                = $data['adventure_id'];
        $userAdventure               = new UserAdventure();
        $userAdventure->user_id      = $data['user_id'];
        $userAdventure->adventure_id = $adventure_id;
        $userAdventure->save();

        for ($i = 1; $i <= 20; $i++) {
            if (isset($data['slot' . $i]) && $data['slot' . $i] != 0) {
                $userAdventureLoot                    = new UserAdventureLoot();
                $userAdventureLoot->user_adventure_id = $userAdventure->id;
                $userAdventureLoot->adventure_loot_id = $data['slot' . $i];
                $userAdventureLoot->save();
            }
        }

        //Clear cache
        Cache::tags('loot')->flush();

        return $userAdventure;
    }


    /**
     * @param $id
     */
    public function delete($id)
    {
        $userAdventure = $this->byId($id);
        $userAdventure->delete();

        //Clear cache
        Cache::tags('loot')->flush();
    }


    /**
     * @param $data
     *
     * @return UserAdventure
     */
    public function update($data)
    {
        DB::beginTransaction();

        $adventure_id                = $data['adventure_id'];
        $userAdventure               = $this->byId($data['user_adventure_id']);
        $userAdventure->adventure_id = $adventure_id;
        $userAdventure->update();

        //This is ugly, but a lot easier since the amount of slots could have changed too.
        UserAdventureLoot::where('user_adventure_id', $userAdventure->id)->delete();

        for ($i = 1; $i <= 20; $i++) {
            if (isset($data['slot' . $i]) && $data['slot' . $i] != 0) {
                $userAdventureLoot                    = new UserAdventureLoot();
                $userAdventureLoot->user_adventure_id = $userAdventure->id;
                $userAdventureLoot->adventure_loot_id = $data['slot' . $i];
                $userAdventureLoot->save();
            }
        }

        DB::commit();

        //Clear cache
        Cache::tags('loot')->flush();

        return $userAdventure;
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function findAllAdventuresForUser($id)
    {
        return UserAdventure::where('user_id', $id)->firstOrFail();
    }


    /**
     * @param $id
     *
     * @return array|static[]
     */
    public function findAllLootForUserAdventure($id)
    {
        return DB::table('user_adventure_loot')
            ->join('adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->where('user_adventure_loot.user_adventure_id', '=', $id)
            ->orderBy('adventure_loot.slot')->get();
    }


    /**
     * @return array|static[]
     */
    public function findAllPlayedAdventures()
    {
        return DB::table('adventure')->join('user_adventure', 'adventure.id', '=', 'user_adventure.adventure_id')
            ->select(['adventure.*', DB::raw('COUNT(*) as played')])
            ->groupBy('adventure.id')
            ->orderBy('name')
            ->get();
    }


    /**
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function getAllAdventuresWithPlayedAndLoot()
    {
        return Adventure::with(['played', 'loot'])->orderBy('name');
    }


    /**
     * @param $adventure_id
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function getAdventureWithPlayedAndLoot($adventure_id)
    {
        return Adventure::with(['played', 'loot'])->where('id', $adventure_id)->orderBy('name');
    }


    /**
     * @return UserAdventure
     */
    public function getAllUserAdventuresWithLoot()
    {
        return UserAdventure::with('loot');
    }


    /**
     * @return array
     */
    public function getLootDropCount()
    {
        return DB::table('adventure_loot')->leftJoin(
            'user_adventure_loot',
            'user_adventure_loot.adventure_loot_id',
            '=',
            'adventure_loot.id'
        )
        ->join('items', 'items.id', '=', 'adventure_loot.item_id')
        ->select(['adventure_loot.*', DB::raw('COUNT(' . \DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')])
        ->groupBy('adventure_loot.id')
            ->orderBy('adventure_id')
            ->orderBy('slot')
            ->orderBy('name')
            ->orderBy('amount')
            ->lists('dropped', 'adventure_loot.id');
    }


    /**
     * @param $adventure_id
     *
     * @return array
     */
    public function getLootDropCountForAdventure($adventure_id)
    {
        return DB::table('adventure_loot')->leftJoin(
            'user_adventure_loot',
            'user_adventure_loot.adventure_loot_id',
            '=',
            'adventure_loot.id'
        )->join('items', 'items.id', '=', 'adventure_loot.item_id')
        ->select(['adventure_loot.*', DB::raw('COUNT(' . DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')])
        ->where('adventure_id', $adventure_id)
            ->groupBy('adventure_loot.id')
            ->orderBy('adventure_id')
            ->orderBy('slot')
            ->orderBy('name')
            ->orderBy('amount')
            ->lists('dropped', 'adventure_loot.id');
    }


    /**
     * @param $adventure_id
     * @param $item_name
     * @param $amount
     *
     * @return AdventureLoot
     */
    public function getLootByNameAndAmount($adventure_id, $slot, $item_name, $amount)
    {
        \Log::debug($item_name . ' ' . $amount);
        $item = Item::where('name', $item_name)->first();
        $lootItem = AdventureLoot::where('item_id', $item->id)->
            where('adventure_id', $adventure_id)->
            where('slot', $slot)->
            where('amount', $amount)->
            first();

        if ($lootItem !== null) {
            return $lootItem;
        }

        throw new \Exception("Adventure ID: $adventure_id, slot: $slot, item name: $item_name, ".
                             "ammount: $amount - Does not exists");
    }

    /**
     * @param $user_id
     * @param $from
     * @param $to
     *
     * @return \Illuminate\Database\Eloquent\Builder|UserAdventure
     */
    public function getAllUserAdventuresForUserWithLoot($user_id, $from, $to)
    {
        $query = UserAdventure::with(['loot'])->where('user_id', $user_id);

        if (( $from != '' ) && ( $to != '' )) {
            $query->whereBetween('user_adventure.created_at', [$from, $to]);
        }

        return $query;
    }
}
