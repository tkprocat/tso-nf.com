<?php namespace LootTracker\Repositories\Stats;

use DB;
use Carbon\Carbon;
use LootTracker\Repositories\Adventure\Adventure;
use LootTracker\Repositories\Loot\LootInterface;

/**
 * Class EloquentPersonalStatsRepository
 * @package LootTracker\Repositories\Stats
 */
class EloquentPersonalStatsRepository implements PersonalStatsInterface
{

    /**
     * @var LootInterface
     */
    protected $loot;


    /**
     * @param LootInterface $loot
     */
    public function __construct(LootInterface $loot)
    {
        $this->loot = $loot;
    }


    /**
     * @param $user_id
     *
     * @return array
     */
    public function getMostPlayedAdventureForUser($user_id)
    {
        $result = array();
        $result['name'] = 'N/A';
        $result['count'] = 0;
        $adventures = $this->getAdventuresForUserWithPlayed($user_id, '', '')->get();
        foreach ($adventures as $adventure) {
            if ($result['count'] < $adventure->played->count()) {
                $result['name'] = $adventure->name;
                $result['count'] = $adventure->played->count();
            }
        }

        return $result;
    }


    /**
     * @param $user_id
     *
     * @return array
     */
    public function getLeastPlayedAdventureForUser($user_id)
    {
        $result = array();
        $result['name'] = 'N/A';
        $result['count'] = 0;
        $adventures = $this->getAdventuresForUserWithPlayed($user_id, '', '')->get();
        foreach ($adventures as $adventure) {
            if ($result['count'] > $adventure->played->count() || $result['count'] == 0) {
                $result['name'] = $adventure->name;
                $result['count'] = $adventure->played->count();
            }
        }

        return $result;
    }


    /**
     * @param $user_id
     *
     * @return mixed
     */
    public function getAdventuresPlayedCountForUserThisWeek($user_id)
    {
        return DB::table('user_adventure')
            ->select(DB::raw('count(*) as registered'))
            ->where('user_adventure.user_id', $user_id)
            ->whereBetween(
                'user_adventure.created_at',
                array(Carbon::today()->startOfWeek(), Carbon::today()->startOfWeek()->addDays(7))
            )->first()->registered;
    }


    /**
     * @param $user_id
     *
     * @return mixed
     */
    public function getAdventuresPlayedCountForUserLastWeek($user_id)
    {
        return DB::table('user_adventure')
            ->select(DB::raw('count(*) as registered'))
            ->where('user_adventure.user_id', $user_id)
            ->whereBetween(
                'user_adventure.created_at',
                array(Carbon::today()->startOfWeek()->subDays(7), Carbon::today()->startOfWeek())
            )->first()->registered;
    }


    /**
     * @param        $user_id
     * @param string $from
     * @param string $to
     *
     * @return mixed
     */
    public function getAdventuresForUserWithPlayed($user_id, $from = '', $to = '')
    {

        $query = Adventure::join('user_adventure', 'user_adventure.adventure_id', '=', 'adventure.id')
            ->select('adventure.*')
            ->where('user_adventure.user_id', $user_id)
            ->with(['played' => function ($query) use ($user_id, $from, $to)
                {
                    $query->where('user_id', '=', $user_id);
                    if (($from != '') && ($to != '')) {
                        $query->whereBetween('created_at', array($from, $to));
                    }
                }, 'loot']
            )->groupBy('adventure.id')->orderBy('name');
        if (($from != '') && ($to != '')) {
            $query->whereBetween('user_adventure.created_at', array($from, $to));
        }

        return $query;
    }


    /**
     * @param        $user_id
     * @param string $from
     * @param string $to
     *
     * @return array
     */
    public function getLootDropCountForUser($user_id, $from = '', $to = '')
    {

        $query = DB::table('adventure_loot')
            ->leftJoin('user_adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->join('user_adventure', 'user_adventure_loot.user_adventure_id', '=', 'user_adventure.id')
            ->select(array(
                'adventure_loot.*',
                DB::raw('COUNT(' . DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')
            ))
            ->groupBy('adventure_loot.id')
            ->where('user_adventure.user_id', $user_id)
            ->orderBy('adventure_id')->orderBy('slot')->orderBy('type')->orderBy('amount');

        if (($from != '') && ($to != '')) {
            $query->whereBetween('user_adventure.created_at', array($from, $to));
        }

        return $query->lists('dropped', 'id');
    }


    /**
     * @param $user_id
     * @param $from_date
     * @param $to_date
     *
     * @return array|static[]
     */
    public function getAccumulatedLootForUser($user_id, $from_date, $to_date)
    {
        return DB::table('user_adventure')
            ->join('user_adventure_loot', 'user_adventure_loot.user_adventure_id', '=', 'user_adventure.id')
            ->join('adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->where('adventure_loot.type', '!=', 'Nothing')
            ->where('user_id', $user_id)
            ->whereBetween('user_adventure.created_at', array($from_date, $to_date))
            ->groupBy('adventure_loot.type')
            ->orderBy('adventure_loot.type')
            ->select(array(DB::raw('SUM(amount) as amount'), 'type'))
            ->get();
    }
}
