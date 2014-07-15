<?php
namespace LootTracker\Stats;

use LootTracker\Loot\LootInterface;

class DbStatsRepository implements StatsInterface
{
    protected $loot;

    public function __construct(LootInterface $loot)
    {
        $this->loot = $loot;
    }

    public function getMostPlayedAdventureForUser($user_id)
    {
        $result = array();
        $result['name'] = 'N/A';
        $result['count'] = 0;
        $adventures = $this->loot->getAdventuresForUserWithPlayed($user_id, '', '');
        $adventures->orderBy('Played');
        //TODO: Find a better way of doing this.
        foreach ($adventures as $adventure)
        {
            if ($result['count'] < $adventure->played) {
                $result['name'] = $adventure->name;
                $result['count'] = $adventure->played;
            }
        }
        return $result;
    }

    public function getLeastPlayedAdventureForUser($user_id)
    {
        $result = array();
        $result['name'] = 'N/A';
        $result['count'] = 0;
        $adventures = $this->loot->getAdventuresForUserWithPlayed($user_id, '', '');
        $adventures->orderBy('Played');
        //TODO: Find a better way of doing this.
        foreach ($adventures as $adventure)
        {
            if ($result['count'] > $adventure->played) {
                $result['name'] = $adventure->name;
                $result['count'] = $adventure->played;
            }
        }
        return $result;
    }

    public function getAdventuresPlayedCountForUserThisWeek($user_id)
    {
        return \DB::table('user_adventure')
            ->select(\DB::raw('count(*) as registered'))
            ->where('user_adventure.user_id', $user_id)
            ->whereBetween('user_adventure.created_at', array(date("Y-m-d", strtotime('this week')), date("Y-m-d", strtotime('tomorrow'))))
            ->first()->registered;
    }

    public function getAdventuresPlayedCountForUserLastWeek($user_id)
    {
        return \DB::table('user_adventure')
            ->select(\DB::raw('count(*) as registered'))
            ->where('user_adventure.user_id', $user_id)
            ->whereBetween('user_adventure.created_at', array(date("Y-m-d", strtotime('last week')), date("Y-m-d", strtotime('last sunday') + 1)))
            ->first()->registered;
    }
}