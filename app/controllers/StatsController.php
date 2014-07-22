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

        $adventures = $this->stats->getAdventuresForUserWithPlayed($user_id, '', '')->get();
        $mostPlayedAdventure = $this->stats->getMostPlayedAdventureForUser($user_id);
        $leastPlayedAdventure = $this->stats->getLeastPlayedAdventureForUser($user_id);
        $stats['most_played_adventure_name'] = $mostPlayedAdventure['name'];
        $stats['most_played_adventure_count'] = $mostPlayedAdventure['count'];
        $stats['least_played_adventure_name'] = $leastPlayedAdventure['name'];
        $stats['least_played_adventure_count'] = $leastPlayedAdventure['count'];
        $stats['adventures_played_this_week'] = $this->stats->getAdventuresPlayedCountForUserThisWeek($user_id);
        $stats['adventures_played_last_week'] = $this->stats->getAdventuresPlayedCountForUserLastWeek($user_id);

        return View::make('stats.personal', compact('adventures', 'stats', 'username'));
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
        $adventures = $this->stats->getAdventuresForUserWithPlayed($user_id, $date_from, $date_to)->get();
        $total_played = $this->loot->getAllUserAdventuresForUserWithLoot($user_id, $date_from, $date_to)->count();
        $drop_count_list = $this->stats->getLootDropCountForUser($user_id, $date_from, $date_to);

        return View::make('stats.partials.adventures_played', compact('adventures', 'total_played', 'drop_count_list'));
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