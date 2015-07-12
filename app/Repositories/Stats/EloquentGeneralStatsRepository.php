<?php namespace LootTracker\Repositories\Stats;

use DB;
use Carbon\Carbon;
use LootTracker\Repositories\Loot\UserAdventure;
use LootTracker\Repositories\User\User;

class EloquentGeneralStatsRepository implements GeneralStatsInterface
{
    public function getSubmissionsForWeek(Carbon $date)
    {
        $startOfWeek = clone $date->startOfWeek();
        $endOfWeek = $date->endOfWeek();

        return UserAdventure::where('created_at', '>=', $startOfWeek)
            ->where('created_at', '<=', $endOfWeek)
            ->get()
            ->count();
    }

    public function getNewUsersForWeek(Carbon $date)
    {
        $startOfWeek = clone $date->startOfWeek();
        $endOfWeek = $date->endOfWeek();

        return User::where('created_at', '>=', $startOfWeek)->where('created_at', '<=', $endOfWeek)->get()->count();
    }

    public function getLootTypes()
    {
        return DB::table('adventure_loot')->select('Type')->distinct()->orderBy('Type')->get();
    }

    public function getLast10Weeks()
    {
        $date = Carbon::now();
        $weeks = [];
        for ($i = 1; $i <= 10; $i++) {
            $weeks[] =  $date->weekOfYear;
            $date = $date->subWeek();
        }
        return $weeks;
    }

    public function getLast30Days()
    {
        $date = Carbon::now();
        $days = [];
        for ($i = 1; $i <= 30; $i++) {
            $days[] = $date->day.'-'.$date->month;
            $date = $date->subDay();
        }
        return $days;
    }
}
