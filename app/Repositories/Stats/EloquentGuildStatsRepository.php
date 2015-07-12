<?php namespace LootTracker\Repositories\Stats;

use Carbon\Carbon;
use DB;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Repositories\Loot\UserAdventure;

/**
 * Class EloquentGuildStatsRepository
 * @package LootTracker\Repositories\Stats
 */
class EloquentGuildStatsRepository implements GuildStatsInterface
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
     * @param        $guild_id
     * @param Carbon $date
     * @param        $adventure_id
     *
     * @return mixed
     */
    public function getPlayedCountPerDayForAdventure($guild_id, Carbon $date, $adventure_id)
    {
        $startOfDay = clone $date->startOfDay();
        $endOfDay = $date->endOfDay();
        return UserAdventure::where('adventure_id', $adventure_id)
            ->where('created_at', '>=', $startOfDay)
            ->where('created_at', '<=', $endOfDay)
            ->get()
            ->count();
    }


    /**
     * @param     $guild_id
     * @param int $adventure_id
     *
     * @return int
     */
    public function getPlayedCount($guild_id, $adventure_id = 0)
    {
        $query = DB::table('user_adventure')
            ->leftJoin('users', 'users.id', '=', 'user_adventure.user_id')
            ->where('users.guild_id', $guild_id);

        if ($adventure_id > 0) {
            $query->where('user_adventure.adventure_id', $adventure_id);
        }

        return $query->count();
    }


    /**
     * @param     $guild_id
     * @param int $adventure_id
     *
     * @return array
     */
    public function getLootDropCount($guild_id, $adventure_id = 0)
    {
        $query = DB::table('adventure_loot')
            ->leftJoin('user_adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->leftJoin('user_adventure', 'user_adventure.id', '=', 'user_adventure_loot.user_adventure_id')
            ->leftJoin('users', 'users.id', '=', 'user_adventure.user_id')
            ->select(array(
                'adventure_loot.*',
                DB::raw('COUNT(' . DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')
            ))
            ->where('users.guild_id', $guild_id)
            ->groupBy('adventure_loot.id')
            ->orderBy('adventure_id')->orderBy('slot')->orderBy('type')->orderBy('amount');

        if ($adventure_id > 0) {
            $query->where('user_adventure.adventure_id', $adventure_id);
        }

        return $query->lists('dropped', 'id');
    }


    /**
     * @param     $guild_id
     * @param int $adventure_id
     *
     * @return array|static[]
     */
    public function getAdventuresWithPlayedAndLoot($guild_id, $adventure_id = 0)
    {
        $query = DB::table('adventure')
            ->leftJoin('user_adventure', 'user_adventure.adventure_id', '=', 'adventure.id')
            ->leftJoin('users', 'users.id', '=', 'user_adventure.user_id')
            ->where('users.guild_id', $guild_id)
            ->groupBy('adventure.id')
            ->select(array(
                'adventure.name',
                'adventure.type',
                DB::raw('COUNT(' . DB::getTablePrefix() . 'user_adventure.id) as played')
            ));

        if ($adventure_id > 0) {
            $query->where('adventure.id', $adventure_id);
        }

        return $query->get();
    }
}
