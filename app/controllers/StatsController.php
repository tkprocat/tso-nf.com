<?php
use Illuminate\Http\Response as LaravelResponse;
use LootTracker\Adventure\AdventureInterface;
use LootTracker\Loot\LootInterface;
use LootTracker\Stats\StatsInterface;

class StatsController extends BaseController
{
    protected $layout = 'layouts.default';

    protected $loot;
    protected $adventure;
    protected $stats;

    function __construct(LootInterface $loot, AdventureInterface $adventure, LootTracker\Stats\StatsInterface $stats)
    {
        $this->loot = $loot;
        $this->adventure = $adventure;
        $this->stats = $stats;
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
        if ($username == '')
            $username = Sentry::getUser()->username;
        $user_id = $this->getUserId($username);

        $adventures = $this->loot->getAdventuresForUserWithPlayed($user_id, '', '')->get();
       // $total_played = $this->loot->getAllAdventuresForUserWithPlayed($user_id)->count();
       // $drop_count_list = $this->loot->getLootDropCountForUser($user_id);


        $mostPlayedAdventure = $this->stats->getMostPlayedAdventureForUser($user_id);
        $leastPlayedAdventure = $this->stats->getLeastPlayedAdventureForUser($user_id);
        $stats['most_played_adventure_name'] = $mostPlayedAdventure['name'];
        $stats['most_played_adventure_count'] = $mostPlayedAdventure['count'];
        $stats['least_played_adventure_name'] = $leastPlayedAdventure['name'];
        $stats['least_played_adventure_count'] = $leastPlayedAdventure['count'];
        $stats['adventures_played_this_week'] = $this->stats->getAdventuresPlayedCountForUserThisWeek($user_id);
        $stats['adventures_played_last_week'] = $this->stats->getAdventuresPlayedCountForUserLastWeek($user_id);

       // return View::make('stats.personal', compact('adventures', 'total_played', 'drop_count_list', 'stats', 'username'));
        return View::make('stats.personal', compact('adventures', 'stats', 'username'));

        /*
        //Check if username is set and get the userid, otherwise fill userid with the current users.
        if ($username == '')
            $username = Sentry::getUser()->username;
        $user_id = $this->getUserId($username);

        // Get all the adventures
        $adventures = DB::table('adventure')
            ->join('user_adventure', 'adventure.id', '=', 'user_adventure.adventure_id')
            ->select(array('adventure.*', DB::raw('COUNT(*) as played')))
            ->where('user_adventure.user_id', $user_id)
            ->groupBy('adventure.id')->orderBy('name')->get();

        $mostPlayedAdventure = "N/A";
        $mostPlayedAdventureCount = 0;
        $leastPlayedAdventure = "N/A";
        $leastPlayedAdventureCount = 0;
        foreach ($adventures as $adventure) {
            if (($mostPlayedAdventureCount == 0) || ($mostPlayedAdventureCount < $adventure->played)) {
                $mostPlayedAdventure = $adventure->name;
                $mostPlayedAdventureCount = $adventure->played;
            }
            if (($leastPlayedAdventureCount == 0) || ($leastPlayedAdventureCount > $adventure->played)) {
                $leastPlayedAdventure = $adventure->name;
                $leastPlayedAdventureCount = $adventure->played;
            }
        }

        $stats['mostPlayedAdventure'] = $mostPlayedAdventure;
        $stats['leastPlayedAdventure'] = $leastPlayedAdventure;
        $stats['adventuresPlayedThisWeek'] = DB::table('user_adventure')
            ->select(DB::raw('count(*) as registered'))
            ->where('user_adventure.user_id', $user_id)
            ->whereBetween('user_adventure.created_at', array(date("Y-m-d", strtotime('this week')), date("Y-m-d", strtotime('tomorrow'))))
            ->first()->registered;
        $stats['adventuresPlayedLastWeek'] = DB::table('user_adventure')
            ->select(DB::raw('count(*) as registered'))
            ->where('user_adventure.user_id', $user_id)
            ->whereBetween('user_adventure.created_at', array(date("Y-m-d", strtotime('last week')), date("Y-m-d", strtotime('last sunday') + 1)))
            ->first()->registered;

        $adventureloot = DB::table('adventure_loot')
            ->leftJoin('user_adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->join('user_adventure', 'user_adventure_loot.user_adventure_id', '=', 'user_adventure.id')
            ->select(array('adventure_loot.*', DB::raw('COUNT(' . \DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')))
            ->where('user_adventure.user_id', $user_id)
            ->groupBy('adventure_loot.id')
            ->orderBy('adventure_id')->orderBy('slot')->orderBy('type')->orderBy('amount')->get();

        $userloot = DB::table('user_adventure')
            ->join('adventure', 'adventure.id', '=', 'user_adventure.adventure_id')
            ->join('user_adventure_loot', 'user_adventure_loot.user_adventure_id', '=', 'user_adventure.id')
            ->join('adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->select(array('user_adventure_loot.adventure_loot_id', 'adventure_loot.adventure_id', 'adventure_loot.Slot', DB::raw('COUNT(*) as dropped')))
            ->where('user_adventure.user_id', $user_id)
            ->groupBy('user_adventure.adventure_id')
            ->groupBy('user_adventure_loot.adventure_loot_id')
            ->orderBy('adventure_loot.adventure_id')->orderBy('adventure_loot.slot')->get();

        //Get information for the top tables
        $count = DB::table('user_adventure')->select(array(DB::raw('COUNT(distinct adventure_id) as Adventures')))->where('user_id', $user_id)->first()->Adventures;

        //Get data if the user has registered anything.
        $topAdventures1 = array();
        $topAdventures2 = array();
        if ($count > 0) {
            $half = (int)($count / 2) + 1;

            $topAdventures1 = DB::table('adventure')
                ->join('user_adventure', 'adventure.id', '=', 'user_adventure.adventure_id')
                ->select(array('name', DB::raw('COUNT(*) as Played'), DB::raw('(select  count(*) from ' . \DB::getTablePrefix() . 'user_adventure where user_id = ' . $user_id . ') as TotalPlayed')))
                ->where('user_adventure.user_id', $user_id)
                ->groupBy('adventure.id')->orderBy('name')->take($half)->get();

            if ($count > 1) {
                $topAdventures2 = DB::table('adventure')
                    ->join('user_adventure', 'adventure.id', '=', 'user_adventure.adventure_id')
                    ->select(array('name', DB::raw('COUNT(*) as Played'), DB::raw('(select  count(*) from ' . \DB::getTablePrefix() . 'user_adventure where user_id = ' . $user_id . ') as TotalPlayed')))
                    ->where('user_adventure.user_id', $user_id)
                    ->groupBy('adventure.id')->orderBy('name')->take($count)->skip($half)->get();
            }
        }

        $useradventures = UserAdventure::where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(10);
        $accumulatedloot = DB::table('user_adventure')
            ->join('user_adventure_loot', 'user_adventure_loot.user_adventure_id', '=', 'user_adventure.id')
            ->join('adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->where('adventure_loot.type', '!=', 'Nothing')
            ->where('user_id', $user_id)
            ->groupBy('adventure_loot.type')
            ->orderBy('adventure_loot.type')
            ->select(array(DB::raw('SUM(amount) as amount'), 'type'))
            ->get();

        return View::make('stats.personal', compact('topAdventures1', 'topAdventures2', 'adventures', 'adventureloot', 'userloot', 'useradventures', 'accumulatedloot', 'stats', 'username'));
        */
    }

    public function getAccumulatedLootBetween($username = '', $dateFrom = '1970-01-01', $dateTo = '2030-31-12')
    {
        //Check if username is set and get the userid, otherwise fill userid with the current users.
        if ($username == '')
            $username = Sentry::getUser()->username;
        $user_id = $this->getUserId($username);

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
        if ($username == '')
            $username = Sentry::getUser()->username;
        $user_id = $this->getUserId($username);

        // Get all played adventures.
        $adventures = $this->loot->getAdventuresForUserWithPlayed($user_id, $date_from, $date_to)->get();
        $total_played = $this->loot->getAllUserAdventuresForUserWithLoot($user_id, $date_from, $date_to)->count();
        $drop_count_list = $this->loot->getLootDropCountForUser($user_id, $date_from, $date_to);

        return View::make('stats.partials.adventures_played', compact('adventures', 'total_played', 'drop_count_list'));

        /*// Get all the adventures
        $adventures = DB::table('adventure')
            ->join('user_adventure', 'adventure.id', '=', 'user_adventure.adventure_id')
            ->select(array('adventure.*', DB::raw('COUNT(*) as played')))
            ->where('user_adventure.user_id', $user_id)
            ->whereBetween('user_adventure.created_at', array($dateFrom, $dateTo))
            ->groupBy('adventure.id')->orderBy('name')->get();

        $adventureloot = DB::table('adventure_loot')
            ->leftJoin('user_adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->join('user_adventure', 'user_adventure_loot.user_adventure_id', '=', 'user_adventure.id')
            ->select(array('adventure_loot.*', DB::raw('COUNT(' . \DB::getTablePrefix() . 'user_adventure_loot.id) as dropped')))
            ->where('user_adventure.user_id', $user_id)
            ->whereBetween('user_adventure.created_at', array($dateFrom, $dateTo))
            ->groupBy('adventure_loot.id')
            ->orderBy('adventure_id')->orderBy('Slot')->orderBy('Type')->orderBy('Amount')->get();

        $userloot = DB::table('user_adventure')
            ->join('adventure', 'adventure.id', '=', 'user_adventure.adventure_id')
            ->join('user_adventure_loot', 'user_adventure_loot.user_adventure_id', '=', 'user_adventure.id')
            ->join('adventure_loot', 'user_adventure_loot.adventure_loot_id', '=', 'adventure_loot.id')
            ->select(array('user_adventure_loot.adventure_loot_id', 'adventure_loot.adventure_id', 'adventure_loot.Slot', DB::raw('COUNT(*) as Dropped')))
            ->where('user_adventure.user_id', $user_id)
            ->whereBetween('user_adventure.created_at', array($dateFrom, $dateTo))
            ->groupBy('user_adventure.adventure_id')
            ->groupBy('user_adventure_loot.adventure_loot_id')
            ->orderBy('adventure_loot.adventure_id')->orderBy('adventure_loot.Slot')->get();

        //Get information for the top tables
        $count = DB::table('user_adventure')
            ->select(array(DB::raw('COUNT(distinct adventure_id) as Adventures')))
            ->where('user_id', $user_id)
            ->whereBetween('user_adventure.created_at', array($dateFrom, $dateTo))
            ->first()->Adventures;

        //Get amount of adventures the player has registered
        $totalPlayed = DB::table('user_adventure')
            ->select(array(DB::raw('COUNT(adventure_id) as Adventures')))
            ->where('user_id', $user_id)
            ->whereBetween('user_adventure.created_at', array($dateFrom, $dateTo))
            ->first()->Adventures;

        $topAdventures1 = array();
        $topAdventures2 = array();
        if ($count > 0) {
            $half = (int)($count / 2);

            $topAdventures1 = DB::table('adventure')
                ->join('user_adventure', 'adventure.id', '=', 'user_adventure.adventure_id')
                ->select(array('name', DB::raw('COUNT(*) as Played')))
                ->where('user_adventure.user_id', $user_id)
                ->whereBetween('user_adventure.created_at', array($dateFrom, $dateTo))
                ->groupBy('adventure.id')->take($half)->orderBy('name')->get();
            if ($count > 1) {
                $topAdventures2 = DB::table('adventure')
                    ->join('user_adventure', 'adventure.id', '=', 'user_adventure.adventure_id')
                    ->select(array('name', DB::raw('COUNT(*) as Played')))
                    ->where('user_adventure.user_id', $user_id)
                    ->whereBetween('user_adventure.created_at', array($dateFrom, $dateTo))
                    ->groupBy('adventure.id')->orderBy('name')->take($count)->skip($half)->get();

            } else {
                $topAdventures2 = \Illuminate\Database\Eloquent\Collection::make(0);
            }
        }
        return View::make('stats.adventuresplayed', compact('topAdventures1', 'topAdventures2', 'adventures', 'adventureloot', 'userloot', 'useradventures', 'totalPlayed'));*/
    }

    private function getUserId($username)
    {
        //Check if username is set and get the userid, otherwise fill userid with the current users.
        if ($username == '')
            return Sentry::getID();
        else
            return Sentry::findUserByLogin($username)->getId();
    }

    public function getJSONLootTypes()
    {
        $lootTypes = DB::table('adventure_loot')
            ->select('Type')->distinct()->orderBy('Type')->get();
        return Response::json($lootTypes);
    }
}