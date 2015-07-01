<?php
namespace LootTracker\Repositories\Stats;

use Carbon\Carbon;

interface GlobalStatsInterface
{
    public function getTop10BestAdventuresForLootTypeByAvgDrop($type);

    public function getPlayedCountPerDayForAdventure(Carbon $date, $adventure_id);
}