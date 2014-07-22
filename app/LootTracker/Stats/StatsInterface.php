<?php
namespace LootTracker\Stats;

interface StatsInterface
{
    public function getMostPlayedAdventureForUser($user_id);

    public function getLeastPlayedAdventureForUser($user_id);

    public function getAdventuresPlayedCountForUserThisWeek($user_id);

    public function getAdventuresPlayedCountForUserLastWeek($user_id);

    public function getAdventuresForUserWithPlayed($user_id, $from, $to);

    public function getLootDropCountForUser($user_id, $from, $to);
}