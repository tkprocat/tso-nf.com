<?php namespace LootTracker\Repositories\Stats;

use Carbon\Carbon;
use DB;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Repositories\Loot\UserAdventure;

/**
 * Class EloquentGlobalStatsRepository
 * @package LootTracker\Repositories\Stats
 */
class EloquentGlobalStatsRepository implements GlobalStatsInterface
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
     * @param $type
     *
     * @return array|static[]
     */
    public function getTop10BestAdventuresForLootTypeByAvgDrop($type)
    {
        $result = DB::table('items')
            ->join('adventure_loot', 'adventure_loot.item_id', '=', 'items.id')
            ->join('user_adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->join('adventure', 'adventure.id', '=', 'adventure_loot.adventure_id')
            ->join('user_adventure', 'user_adventure.id', '=', 'user_adventure_loot.user_adventure_id')
            ->select(array('adventure.name',
                DB::raw('sum(adventure_loot.amount) as totalDropped'),
                DB::raw('(select count(*) from user_adventure where user_adventure.adventure_id = adventure.id) as totalPlayed'),
                DB::raw('(sum(adventure_loot.amount) / (select count(*) from user_adventure where user_adventure.adventure_id = adventure.id)) as avgDrop')))
            ->where('items.name', $type)
            ->groupBy('adventure.id')
            ->orderBy('avgDrop', 'desc')
            ->orderBy('adventure.name')->take(10)->get();
        return $result;
    }


    /**
     * @param Carbon $date
     * @param        $adventure_id
     *
     * @return mixed
     */
    public function getPlayedCountPerDayForAdventure(Carbon $date, $adventure_id)
    {
        $startOfDay = clone $date->startOfDay();
        $endOfDay = $date->endOfDay();
        return UserAdventure::where('adventure_id', $adventure_id)->where('created_at', '>=', $startOfDay)->where('created_at', '<=', $endOfDay)->get()->count();
    }
}
