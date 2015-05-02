<?php
use LootTracker\Adventure\AdventureInterface;
use LootTracker\Loot\LootInterface;
use LootTracker\Stats\StatsInterface;
use Authority\Repo\User\UserInterface;

class StatsController extends BaseController
{
    protected $layout = 'layouts.default';

    protected $loot;
    protected $adventure;
    protected $stats;
    protected $user;

    function __construct(LootInterface $loot, AdventureInterface $adventure, StatsInterface $stats, UserInterface $user)
    {
        $this->loot = $loot;
        $this->adventure = $adventure;
        $this->stats = $stats;
        $this->user = $user;
    }

    public function getGlobalStats()
    {
        // Get all played adventures.
        $adventures = $this->loot->getAllAdventuresWithPlayedAndLoot()->get();
        $total_played = $this->loot->getAllUserAdventuresWithLoot()->count();
        $drop_count_list = $this->loot->getLootDropCount();

        return View::make('stats.global', compact('adventures', 'total_played', 'drop_count_list'));
    }

    public function getPersonalStats($username = '')
    {
        //Check if username is set and get the userid, otherwise fill userid with the current users.
        if ($username == '') {
            $user = $this->user->getUser();
            $user_id = $user->id;
            $username = $user->username;
        } else {
            $user_id = $this->user->byUsername($username)->id;
        }

        $mostPlayedAdventure = $this->stats->getMostPlayedAdventureForUser($user_id);
        $leastPlayedAdventure = $this->stats->getLeastPlayedAdventureForUser($user_id);
        $stats['most_played_adventure_name'] = $mostPlayedAdventure['name'];
        $stats['most_played_adventure_count'] = $mostPlayedAdventure['count'];
        $stats['least_played_adventure_name'] = $leastPlayedAdventure['name'];
        $stats['least_played_adventure_count'] = $leastPlayedAdventure['count'];
        $stats['adventures_played_this_week'] = $this->stats->getAdventuresPlayedCountForUserThisWeek($user_id);
        $stats['adventures_played_last_week'] = $this->stats->getAdventuresPlayedCountForUserLastWeek($user_id);

        return View::make('stats.personal', compact('stats', 'username'));
    }

    public function getAccumulatedLootBetween($username = '', $dateFrom = '1970-01-01', $dateTo = '2030-31-12')
    {
        //Check if username is set and get the userid, otherwise fill userid with the current users.
        if ($username == '') {
            $user_id = $this->user->getUserID();
        } else {
            $user_id = $this->user->byUsername($username)->id;
        }

        $accumulatedloot = DB::table('user_adventure')
            ->join('user_adventure_loot', 'user_adventure_loot.user_adventure_id', '=', 'user_adventure.id')
            ->join('adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->where('adventure_loot.type', '!=', 'Nothing')
            ->where('user_id', $user_id)
            ->whereBetween('user_adventure.created_at', array($dateFrom, $dateTo))
            ->groupBy('adventure_loot.type')
            ->orderBy('adventure_loot.type')
            ->select(array(DB::raw('SUM(amount) as amount'), 'type'))
            ->get();
        return View::make('stats.partials.accumulated_loot', compact('accumulatedloot'));
    }

    public function getAdventuresPlayedBetween($username = '', $date_from = '1970-01-01', $date_to = '2030-31-12')
    {
        //Check if username is set and get the userid, otherwise fill userid with the current users.
        if ($username == '') {
            $user = $this->user->getUser();
            $user_id = $user->id;
            $username = $user->username;
        } else {
            $user_id = $this->user->byUsername($username)->id;
        }

        // Get all played adventures.
        $adventures = $this->stats->getAdventuresForUserWithPlayed($user_id, $date_from, $date_to)->get();
        $total_played = $this->loot->getAllUserAdventuresForUserWithLoot($user_id, $date_from, $date_to)->count();
        $drop_count_list = $this->stats->getLootDropCountForUser($user_id, $date_from, $date_to);

        return View::make('stats.partials.adventures_played', compact('adventures', 'total_played', 'drop_count_list', 'username'));
    }

    public function getJSONLootTypes()
    {
        $lootTypes = DB::table('adventure_loot')->select('Type')->distinct()->orderBy('Type')->get();
        return Response::json($lootTypes);
    }


    public function showTop10BestAdventuresForLootTypeByAvgDrop()
    {
        $loot_types = $this->adventure->findAllDifferentLootTypes();
        return View::make('stats.top10byavgdrop', compact('loot_types'));
    }

    public function showTop10BestAdventuresForLootTypeByDropChance()
    {
        $loot_types = $this->adventure->findAllDifferentLootTypes();
        return View::make('stats.top10byavgdropchance', compact('loot_types'));
    }

    public function getTop10BestAdventuresForLootTypeByAvgDrop($type){
        $result = DB::table('adventure_loot')
            ->join('user_adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->join('adventure', 'adventure.id', '=', 'adventure_loot.adventure_id')
            ->select(array('adventure.name', 'adventure_loot.type', DB::raw('((COUNT(\'' . \DB::getTablePrefix() . 'user_adventure_loot.*\' ) * ' . \DB::getTablePrefix() . 'adventure_loot.Amount) DIV (SELECT count( * ) FROM ' . \DB::getTablePrefix() . 'user_adventure WHERE adventure_id = ' . \DB::getTablePrefix() . 'adventure_loot.adventure_id GROUP BY adventure_id )) AS avg_drop')))
            ->where('adventure_loot.type', $type)
            ->groupBy('adventure_loot.adventure_id')
            ->orderBy('avg_drop', 'desc')->take(10)->get();
        return Response::json($result);
    }

    public function getTop10BestAdventuresForLootTypeByDropChance($type){
        $result = DB::table('adventure_loot')
            ->join('user_adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->join('adventure', 'adventure.id', '=', 'adventure_loot.adventure_id')
            ->select(array('adventure.name', 'adventure_loot.type', DB::raw('(COUNT(\'' . \DB::getTablePrefix() . 'user_adventure_loot.*\' ) / (SELECT count( * ) FROM ' . \DB::getTablePrefix() . 'user_adventure WHERE Adventure_ID = ' . \DB::getTablePrefix() . 'adventure_loot.adventure_id GROUP BY adventure_id ) * 100) AS drop_chance'), DB::raw('(SELECT count( * ) FROM ' . \DB::getTablePrefix() . 'user_adventure WHERE adventure_id = ' . \DB::getTablePrefix() . 'adventure_loot.adventure_id GROUP BY adventure_id) AS played')))
            ->where('adventure_loot.type', $type)
            ->groupBy('adventure_loot.adventure_id')
            ->orderBy('drop_chance', 'Desc')->take(10)->get();
        return Response::json($result);
    }

    public function getLast10Weeks()
    {
        $date = \Carbon\Carbon::now();
        for ($i = 1; $i <= 10; $i++)
        {
            $weeks[] =  $date->weekOfYear;
            $date = $date->subWeek();
        }
        return Response::json(array_reverse($weeks));
    }

    public function getSubmissionsForTheLast10Weeks()
    {
        $date = \Carbon\Carbon::now();
        for ($i = 1; $i <= 10; $i++)
        {
            $weeks[] = $this->stats->getSubmissionsForWeek($date);
            $date = $date->subWeek();
        }
        return Response::json(array_reverse($weeks));
    }

    public function getNewUserCountForTheLast10Weeks()
    {
        $date = \Carbon\Carbon::now();
        for ($i = 1; $i <= 10; $i++)
        {
            $weeks[] = $this->stats->getNewUsersForWeek($date);
            $date = $date->subWeek();
        }
        return Response::json(array_reverse($weeks));
    }
}