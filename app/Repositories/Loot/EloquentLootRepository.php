<?php
namespace LootTracker\Repositories\Loot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use LootTracker\Repositories\Adventure\Adventure;

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
     * @param $itemsPerPage
     * @param string $adventure_name
     * @param int $user_id
     * @return mixed
     */
    public function paginate($itemsPerPage, $adventure_name = '', $user_id = 0)
    {
        if ($adventure_name != '') {
            $adventureRepo = App::make('LootTracker\Repositories\Adventure\AdventureInterface');
            $adventure = $adventureRepo->byName(urldecode($adventure_name));
            //Check if we have the adventure
            if ($adventure != null) {
                return UserAdventure::where('adventure_id', $adventure->id)->orderBy('created_at',
                    'desc')->paginate($itemsPerPage);
            } else {
                return UserAdventure::orderBy('created_at', 'desc')->paginate($itemsPerPage);
            } //Return all if we can't find it.
        } elseif ($user_id > 0) {
            return UserAdventure::whereUserId($user_id)->orderBy('created_at', 'desc')->paginate($itemsPerPage);
        } else {
            return UserAdventure::orderBy('created_at', 'desc')->paginate($itemsPerPage);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|Model|static
     */
    public function byId($id)
    {
        return UserAdventure::findOrFail($id);
    }

    /**
     * @param $data
     */
    public function create($data)
    {
        $adventure_id = $data['adventure_id'];
        $useradventure = new UserAdventure();
        $useradventure->user_id = $data['user_id'];
        $useradventure->adventure_id = $adventure_id;
        $useradventure->save();

        for ($i = 1; $i <= 20; $i++) {
            if (isset($data['slot' . $i]) && $data['slot' . $i] != 0) {
                $useradventureloot = new UserAdventureLoot();
                $useradventureloot->user_adventure_id = $useradventure->id;
                $useradventureloot->adventure_loot_id = $data['slot' . $i];
                $useradventureloot->save();
            }
        }
    }

    /**
     * @param $data
     */
    public function update($data)
    {
        DB::beginTransaction();

        $adventure_id = $data['adventure_id'];
        $user_adventure = $this->byId($data['user_adventure_id']);
        $user_adventure->adventure_id = $adventure_id;
        $user_adventure->update();

        //This is ugly, but a lot easier since the amount of slots could have changed too.
        UserAdventureLoot::where('user_adventure_id', $user_adventure->id)->delete();

        for ($i = 1; $i <= 20; $i++) {
            if (isset($data['slot' . $i]) && $data['slot' . $i] != 0) {
                $user_adventure_loot = new UserAdventureLoot();
                $user_adventure_loot->user_adventure_id = $user_adventure->id;
                $user_adventure_loot->adventure_loot_id = $data['slot' . $i];
                $user_adventure_loot->save();
            }
        }

        DB::commit();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findAllAdventuresForUser($id)
    {
        return UserAdventure::where('user_id', $id)->firstOrFail();
    }

    /**
     * @param $id
     * @return array|static[]
     */
    public function findAllLootForUserAdventure($id)
    {
        return DB::table('user_adventure_loot')
            ->join('adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->where('user_adventure_loot.user_adventure_id', '=', $id)
            ->orderBy('adventure_loot.slot')
            ->get();
    }

    /**
     * @return array|static[]
     */
    public function findAllPlayedAdventures()
    {
        return DB::table('adventure')
            ->join('user_adventure', 'adventure.id', '=', 'user_adventure.adventure_id')
            ->select(array('adventure.*', \DB::raw('COUNT(*) as played')))
            ->groupBy('adventure.id')->orderBy('name')->get();
    }

    /**
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function getAllAdventuresWithPlayedAndLoot()
    {
        return Adventure::with(array('played', 'loot'))->orderBy('name');
    }

    /**
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function getAdventureWithPlayedAndLoot($adventure_id)
    {
        return Adventure::with(array('played', 'loot'))->where('id', $adventure_id)->orderBy('name');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Builder|static
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
        return DB::table('adventure_loot')
            ->leftJoin('user_adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->select(array(
                'adventure_loot.*',
                \DB::raw('COUNT(' . \DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')
            ))
            ->groupBy('adventure_loot.id')
            ->orderBy('adventure_id')->orderBy('slot')->orderBy('type')->orderBy('amount')->lists('dropped', 'id');
    }

    /**
     * @return array
     */
    public function getLootDropCountForAdventure($adventure_id)
    {
        return DB::table('adventure_loot')
            ->leftJoin('user_adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->select(array(
                'adventure_loot.*',
                \DB::raw('COUNT(' . \DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')
            ))
            ->where('adventure_id', $adventure_id)
            ->groupBy('adventure_loot.id')
            ->orderBy('adventure_id')->orderBy('slot')->orderBy('type')->orderBy('amount')->lists('dropped', 'id');
    }

    /**
     * @param $user_id
     * @param $from
     * @param $to
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function getAllUserAdventuresForUserWithLoot($user_id, $from, $to)
    {
        $query = UserAdventure::with(array('loot'))->where('user_id', $user_id);

        if (($from != '') && ($to != '')) {
            $query->whereBetween('user_adventure.created_at', array($from, $to));
        }

        return $query;
    }
}
