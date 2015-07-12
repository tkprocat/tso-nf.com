<?php namespace LootTracker\Repositories\Stats;

use Carbon\Carbon;

interface GeneralStatsInterface
{
    public function getSubmissionsForWeek(Carbon $date);

    public function getNewUsersForWeek(Carbon $date);

    public function getLootTypes();

    public function getLast10Weeks();

    public function getLast30Days();
}
