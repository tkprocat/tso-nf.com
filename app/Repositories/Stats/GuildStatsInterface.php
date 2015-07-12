<?php namespace LootTracker\Repositories\Stats;

use Carbon\Carbon;

interface GuildStatsInterface
{
    public function getPlayedCountPerDayForAdventure($guild_id, Carbon $date, $adventure_id);

    public function getPlayedCount($guild_id, $adventure_id = 0);

    public function getLootDropCount($guild_id, $adventure_id = 0);

    public function getAdventuresWithPlayedAndLoot($guild_id, $adventure_id = 0);
}
