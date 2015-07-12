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
        return DB::table('adventure_loot')
            ->join('user_adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->join('adventure', 'adventure.id', '=', 'adventure_loot.adventure_id')
            ->select(array('adventure.name', 'adventure_loot.type', DB::raw('((COUNT(\'' . \DB::getTablePrefix() . 'user_adventure_loot.*\' ) * ' . \DB::getTablePrefix() . 'adventure_loot.Amount) / (SELECT count( * ) FROM ' . \DB::getTablePrefix() . 'user_adventure WHERE adventure_id = ' . \DB::getTablePrefix() . 'adventure_loot.adventure_id GROUP BY adventure_id )) AS avg_drop')))
            ->where('adventure_loot.type', $type)
            ->groupBy('adventure_loot.adventure_id')
            ->orderBy('avg_drop', 'desc')->take(10)->get();
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
