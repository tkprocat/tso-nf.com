<?php
namespace LootTracker\Loot;

use Authority\Repo\User\UserInterface;
use Illuminate\Database\Eloquent\Model;

class DbLootRepository implements LootInterface
{
    protected $userAdventure;
    protected $user;
    public $validator;

    public function __construct(Model $userAdventure, UserInterface $user, LootFormValidator $validator)
    {
        $this->userAdventure = $userAdventure;
        $this->user = $user;
        $this->validator = $validator;
    }

    public function all()
    {
        return $this->loot->all();
    }

    public function findPage($page, $lootPerPage)
    {
        $result = new \StdClass;
        $result->page = $page;
        $result->limit = $lootPerPage;
        $result->totalItems = 0;
        $result->items = array();
        $query = $this->userAdventure->orderBy('created_at', 'desc');
        $loot = $query->skip($lootPerPage * ($page - 1))->take($lootPerPage)->get();
        $result->items = $loot->all();
        $result->totalItems = $loot->count();
        return $result;
    }

    public function findUserAdventureById($id)
    {
        return $this->userAdventure->findOrFail($id);
    }

    public function create($data)
    {
        $adventure_id = $data['adventure_id'];
        $useradventure = new UserAdventure();
        $useradventure->user_id = $data['user_id'];
        $useradventure->adventure_id = $adventure_id;
        $useradventure->save();

        if (isset($data['slot1']) && $data['slot1'] != 0) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->user_adventure_id = $useradventure->id;
            $useradventureloot->adventure_loot_id = $data['slot1'];
            $useradventureloot->save();
        }
        if (isset($data['slot2']) && $data['slot2'] != 0) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->user_adventure_id = $useradventure->id;
            $useradventureloot->adventure_loot_id = $data['slot2'];
            $useradventureloot->save();
        }
        if (isset($data['slot3']) && $data['slot3'] != 0) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->user_adventure_id = $useradventure->id;
            $useradventureloot->adventure_loot_id = $data['slot3'];
            $useradventureloot->save();
        }
        if (isset($data['slot4']) && $data['slot4'] != 0) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->user_adventure_id = $useradventure->id;
            $useradventureloot->adventure_loot_id = $data['slot4'];
            $useradventureloot->save();
        }
        if (isset($data['slot5']) && $data['slot5'] != 0) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->user_adventure_id = $useradventure->id;
            $useradventureloot->adventure_loot_id = $data['slot5'];
            $useradventureloot->save();
        }
        if (isset($data['slot6']) && $data['slot6'] != 0) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->user_adventure_id = $useradventure->id;
            $useradventureloot->adventure_loot_id = $data['slot6'];
            $useradventureloot->save();
        }
        if (isset($data['slot7']) && $data['slot7'] != 0) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->user_adventure_id = $useradventure->id;
            $useradventureloot->adventure_loot_id = $data['slot7'];
            $useradventureloot->save();
        }
        if (isset($data['slot8']) && $data['slot8'] != 0) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->user_adventure_id = $useradventure->id;
            $useradventureloot->adventure_loot_id = $data['slot8'];
            $useradventureloot->save();
        }
    }

    public function update($data)
    {
        \DB::beginTransaction();

        $adventure_id = $data['adventure_id'];
        $user_adventure = $this->findUserAdventureById($data['user_adventure_id']);
        $user_adventure->adventure_id = $adventure_id;
        $user_adventure->update();

        //This is ugly, but a lot easier since the amount of slots could have changed too.
        UserAdventureLoot::where('user_adventure_id', $user_adventure->id)->delete();

        if (isset($data['slot1']) && $data['slot1'] != 0) {
            $user_adventure_loot = new UserAdventureLoot();
            $user_adventure_loot->user_adventure_id = $user_adventure->id;
            $user_adventure_loot->adventure_loot_id = $data['slot1'];
            $user_adventure_loot->save();
        }
        if (isset($data['slot2']) && $data['slot2'] != 0) {
            $user_adventure_loot = new UserAdventureLoot();
            $user_adventure_loot->user_adventure_id = $user_adventure->id;
            $user_adventure_loot->adventure_loot_id = $data['slot2'];
            $user_adventure_loot->save();
        }
        if (isset($data['slot3']) && $data['slot3'] != 0) {
            $user_adventure_loot = new UserAdventureLoot();
            $user_adventure_loot->user_adventure_id = $user_adventure->id;
            $user_adventure_loot->adventure_loot_id = $data['slot3'];
            $user_adventure_loot->save();
        }
        if (isset($data['slot4']) && $data['slot4'] != 0) {
            $user_adventure_loot = new UserAdventureLoot();
            $user_adventure_loot->user_adventure_id = $user_adventure->id;
            $user_adventure_loot->adventure_loot_id = $data['slot4'];
            $user_adventure_loot->save();
        }
        if (isset($data['slot5']) && $data['slot5'] != 0) {
            $user_adventure_loot = new UserAdventureLoot();
            $user_adventure_loot->user_adventure_id = $user_adventure->id;
            $user_adventure_loot->adventure_loot_id = $data['slot5'];
            $user_adventure_loot->save();
        }
        if (isset($data['slot6']) && $data['slot6'] != 0) {
            $user_adventure_loot = new UserAdventureLoot();
            $user_adventure_loot->user_adventure_id = $user_adventure->id;
            $user_adventure_loot->adventure_loot_id = $data['slot6'];
            $user_adventure_loot->save();
        }
        if (isset($data['slot7']) && $data['slot7'] != 0) {
            $user_adventure_loot = new UserAdventureLoot();
            $user_adventure_loot->user_adventure_id = $user_adventure->id;
            $user_adventure_loot->adventure_loot_id = $data['slot7'];
            $user_adventure_loot->save();
        }
        if (isset($data['slot8']) && $data['slot8'] != 0) {
            $user_adventure_loot = new UserAdventureLoot();
            $user_adventure_loot->user_adventure_id = $user_adventure->id;
            $user_adventure_loot->adventure_loot_id = $data['slot8'];
            $user_adventure_loot->save();
        }

        \DB::commit();
    }

    public function findAllAdventuresForUser($id)
    {
        return $this->userAdventure->where('user_id', $id);
    }

    public function findAllLootForUserAdventure($id)
    {
        return \DB::table('user_adventure_loot')
            ->join('adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->where('user_adventure_loot.user_adventure_id', '=', $id)
            ->orderBy('adventure_loot.slot')
            ->get();
    }

    public function findAllPlayedAdventures()
    {
        return \DB::table('adventure')
            ->join('user_adventure', 'adventure.id', '=', 'user_adventure.adventure_id')
            ->select(array('adventure.*', \DB::raw('COUNT(*) as played')))
            ->groupBy('adventure.id')->orderBy('name')->get();
    }

    public function getAdventuresForUserWithPlayed($user_id, $from, $to)
    {
        $query = \LootTracker\Adventure\Adventure::join('user_adventure', 'user_adventure.adventure_id', '=', 'adventure.id')->
            select('adventure.*')->
            where('user_adventure.user_id', $user_id)->with(array('played' => function ($query) use ($user_id, $from, $to) {
                $query->where('user_id', '=', $user_id)->whereBetween('created_at', array($from, $to));
            }, 'loot'))->
			groupBy('adventure.id')->
			orderBy('name');
        return $query;
    }

    public function getAllAdventuresWithPlayedAndLoot()
    {
        return \LootTracker\Adventure\Adventure::with(array('played', 'loot'))->orderBy('name');
    }

    public function getAllUserAdventuresWithLoot()
    {
        return $this->userAdventure->with('loot');
    }

    public function getAllUserAdventuresForUserWithLoot($user_id, $from, $to)
    {
        $query = $this->userAdventure->with(array('loot'))->where('user_id', $user_id);

        if (($from != '') && ($to != ''))
            $query->whereBetween('user_adventure.created_at', array($from, $to));

        return $query;
    }

    public function findLootCountForAllPlayedAdventures()
    {
        return \DB::table('adventure_loot')
            ->leftJoin('user_adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->select(array('adventure_loot.*', \DB::raw('COUNT(' . \DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')))
            ->groupBy('adventure_loot.id')
            ->orderBy('adventure_id')->orderBy('slot')->orderBy('type')->orderBy('amount')->get();
        dd('findLootCountForAllPlayedAdventures was used!');
    }

    public function getLootDropCount()
    {
        return \DB::table('adventure_loot')
            ->leftJoin('user_adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->select(array('adventure_loot.*', \DB::raw('COUNT(' . \DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')))
            ->groupBy('adventure_loot.id')
            ->orderBy('adventure_id')->orderBy('slot')->orderBy('type')->orderBy('amount')->lists('dropped', 'id');
    }

    public function getLootDropCountForUser($user_id, $from = '', $to = '')
    {

        $query = \DB::table('adventure_loot')
            ->leftJoin('user_adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->join('user_adventure', 'user_adventure_loot.user_adventure_id', '=', 'user_adventure.id')
            ->select(array('adventure_loot.*', \DB::raw('COUNT(' . \DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')))
            ->groupBy('adventure_loot.id')
            ->where('user_adventure.user_id', $user_id)
            ->orderBy('adventure_id')->orderBy('slot')->orderBy('type')->orderBy('amount');

        if (($from != '') && ($to != ''))
            $query->whereBetween('user_adventure.created_at', array($from, $to));

        return $query->lists('dropped', 'id');
    }
}