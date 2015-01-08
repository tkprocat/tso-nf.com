<?php
namespace LootTracker\Loot;

use Authority\Repo\User\UserInterface;
use Illuminate\Database\Eloquent\Model;
use LootTracker\Adventure\Adventure;

class DbLootRepository implements LootInterface
{
    protected $userAdventure;
    protected $user;
    public $validator;

    /**
     * @param Model $userAdventure
     * @param UserInterface $user
     * @param LootFormValidator $validator
     */
    public function __construct(Model $userAdventure, UserInterface $user, LootFormValidator $validator)
    {
        $this->userAdventure = $userAdventure;
        $this->user = $user;
        $this->validator = $validator;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->loot->all();
    }

    /**
     * @param $page
     * @param $lootPerPage
     * @return \StdClass
     */
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

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|Model|static
     */
    public function findUserAdventureById($id)
    {
        return $this->userAdventure->findOrFail($id);
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
            if (isset($data['slot'.$i]) && $data['slot'.$i] != 0) {
                $useradventureloot = new UserAdventureLoot();
                $useradventureloot->user_adventure_id = $useradventure->id;
                $useradventureloot->adventure_loot_id = $data['slot'.$i];
                $useradventureloot->save();
            }
        }
    }

    /**
     * @param $data
     */
    public function update($data)
    {
        \DB::beginTransaction();

        $adventure_id = $data['adventure_id'];
        $user_adventure = $this->findUserAdventureById($data['user_adventure_id']);
        $user_adventure->adventure_id = $adventure_id;
        $user_adventure->update();

        //This is ugly, but a lot easier since the amount of slots could have changed too.
        UserAdventureLoot::where('user_adventure_id', $user_adventure->id)->delete();

        for ($i = 1; $i <= 20; $i++) {
            if (isset($data['slot'.$i]) && $data['slot'.$i] != 0) {
                $user_adventure_loot = new UserAdventureLoot();
                $user_adventure_loot->user_adventure_id = $user_adventure->id;
                $user_adventure_loot->adventure_loot_id = $data['slot'.$i];
                $user_adventure_loot->save();
            }
        }

        \DB::commit();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findAllAdventuresForUser($id)
    {
        return $this->userAdventure->where('user_id', $id);
    }

    /**
     * @param $id
     * @return array|static[]
     */
    public function findAllLootForUserAdventure($id)
    {
        return \DB::table('user_adventure_loot')
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
        return \DB::table('adventure')
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
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function getAllUserAdventuresWithLoot()
    {
        return $this->userAdventure->with('loot');
    }

    /**
     * @return array|static[]
     */
    public function findLootCountForAllPlayedAdventures()
    {
        return \DB::table('adventure_loot')
            ->leftJoin('user_adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->select(array('adventure_loot.*', \DB::raw('COUNT(' . \DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')))
            ->groupBy('adventure_loot.id')
            ->orderBy('adventure_id')->orderBy('slot')->orderBy('type')->orderBy('amount')->get();
        dd('findLootCountForAllPlayedAdventures was used!');
        //TODO: We should remove this one day...
    }

    /**
     * @return array
     */
    public function getLootDropCount()
    {
        return \DB::table('adventure_loot')
            ->leftJoin('user_adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->select(array('adventure_loot.*', \DB::raw('COUNT(' . \DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')))
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
        $query = $this->userAdventure->with(array('loot'))->where('user_id', $user_id);

        if (($from != '') && ($to != ''))
            $query->whereBetween('user_adventure.created_at', array($from, $to));

        return $query;
    }
}
