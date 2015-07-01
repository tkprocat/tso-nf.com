<?php namespace LootTracker\Http\Controllers;

use DB;
use Response;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Repositories\Stats\GlobalStatsInterface;
use LootTracker\Repositories\User\UserInterface;

class GlobalStatsController extends Controller
{
    protected $layout = 'layouts.default';

    protected $loot;
    protected $adventure;
    protected $stats;
    protected $user;

    function __construct(LootInterface $loot, AdventureInterface $adventure, GlobalStatsInterface $stats, UserInterface $user)
    {
        $this->loot = $loot;
        $this->adventure = $adventure;
        $this->stats = $stats;
        $this->user = $user;
    }

    public function index()
    {
        // Get all played adventures.
        $adventures = $this->loot->getAllAdventuresWithPlayedAndLoot()->get();
        $adventures = $adventures->sortBy(function ($adventure) {
            return $adventure->type . ' - ' . $adventure->name;
        });
        $total_played = $this->loot->getAllUserAdventuresWithLoot()->count();
        $drop_count_list = $this->loot->getLootDropCount();

        return view('stats.global.index', compact('adventures', 'total_played', 'drop_count_list'));
    }

    public function show($adventureName)
    {
        // Find adventure
        $adventure = $this->adventure->byName(urldecode($adventureName));

        // Get all played adventures.
        $adventure = $this->loot->getAdventureWithPlayedAndLoot($adventure->id)->first();
        $total_played = $this->loot->getAllUserAdventuresWithLoot()->count();
        $drop_count_list = $this->loot->getLootDropCountForAdventure($adventure->id);

        return view('stats.global.show', compact('adventure', 'total_played', 'drop_count_list'));
    }

    public function showSubmissionRate()
    {
        return view('stats.global.submissionrate');
    }

    public function showNewUserRate()
    {
        return view('stats.global.newuserrate');
    }

    public function showTop10BestAdventuresForLootTypeByAvgDrop()
    {
        $loot_types = $this->adventure->findAllDifferentLootTypes();
        return view('stats.global.top10byavgdrop', ['loot_types' => $loot_types]);
    }

    public function getTop10BestAdventuresForLootTypeByAvgDrop($type)
    {
        $result = $this->stats->getTop10BestAdventuresForLootTypeByAvgDrop($type);
        return Response::json($result);
    }

    public function getPlayedCountForLast30Days($adventureName)
    {
        // Find adventure
        $adventure = $this->adventure->byName(urldecode($adventureName));

        $date = \Carbon\Carbon::now();
        for ($i = 1; $i <= 30; $i++)
        {
            $weeks[] = $this->stats->getPlayedCountPerDayForAdventure($date, $adventure->id);
            $date = $date->subDay();
        }
        return Response::json(array_reverse($weeks));
    }
}