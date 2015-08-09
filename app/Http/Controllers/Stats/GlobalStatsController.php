<?php namespace LootTracker\Http\Controllers;

use Response;
use Carbon\Carbon;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Repositories\Stats\GlobalStatsInterface;
use LootTracker\Repositories\User\UserInterface;

/**
 * Class GlobalStatsController
 * @package LootTracker\Http\Controllers
 */
class GlobalStatsController extends Controller
{

    /**
     * @var string
     */
    protected $layout = 'layouts.default';

    /**
     * @var LootInterface
     */
    protected $lootRepo;

    /**
     * @var AdventureInterface
     */
    protected $adventureRepo;

    /**
     * @var GlobalStatsInterface
     */
    protected $statsRepo;

    /**
     * @var UserInterface
     */
    protected $userRepo;


    /**
     * @param LootInterface        $loot
     * @param AdventureInterface   $adventure
     * @param GlobalStatsInterface $stats
     * @param UserInterface        $user
     */
    public function __construct(
        LootInterface $loot,
        AdventureInterface $adventure,
        GlobalStatsInterface $stats,
        UserInterface $user
    ) {
        $this->lootRepo = $loot;
        $this->adventureRepo = $adventure;
        $this->statsRepo = $stats;
        $this->userRepo = $user;
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all played adventures.
        $adventures = $this->lootRepo->getAllAdventuresWithPlayedAndLoot()->get();
        $adventures = $adventures->sortBy(function ($adventure) {
            return $adventure->type . ' - ' . $adventure->name;
        });
        $total_played = $this->lootRepo->getAllUserAdventuresWithLoot()->count();
        $drop_count_list = $this->lootRepo->getLootDropCount();

        return view('stats.global.index', compact('adventures', 'total_played', 'drop_count_list'));
    }


    /**
     * @param $adventureName
     *
     * @return \Illuminate\View\View
     */
    public function show($adventureName)
    {
        // Find adventure
        $adventure = $this->adventureRepo->byName(urldecode($adventureName));

        // Get all played adventures.
        $adventure = $this->lootRepo->getAdventureWithPlayedAndLoot($adventure->id)->first();
        $total_played = $this->lootRepo->getAllUserAdventuresWithLoot()->count();
        $drop_count_list = $this->lootRepo->getLootDropCountForAdventure($adventure->id);
        dd($drop_count_list);

        return view('stats.global.show', compact('adventure', 'total_played', 'drop_count_list'));
    }


    /**
     * @return \Illuminate\View\View
     */
    public function showSubmissionRate()
    {
        return view('stats.global.submissionrate');
    }


    /**
     * @return \Illuminate\View\View
     */
    public function showNewUserRate()
    {
        return view('stats.global.newuserrate');
    }


    /**
     * @return \Illuminate\View\View
     */
    public function showTop10BestAdventuresForLootTypeByAvgDrop()
    {
        $loot_types = $this->adventureRepo->findAllDifferentLootTypes();
        return view('stats.global.top10byavgdrop', ['loot_types' => $loot_types]);
    }


    /**
     * @param $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTop10BestAdventuresForLootTypeByAvgDrop($type)
    {
        $result = $this->statsRepo->getTop10BestAdventuresForLootTypeByAvgDrop($type);
        return Response::json($result);
    }


    /**
     * @param $adventureName
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlayedCountForLast30Days($adventureName)
    {
        // Find adventure
        $adventure = $this->adventureRepo->byName(urldecode($adventureName));

        $date = Carbon::now();
        $weeks = [];
        for ($i = 1; $i <= 30; $i++) {
            $weeks[] = $this->statsRepo->getPlayedCountPerDayForAdventure($date, $adventure->id);
            $date = $date->subDay();
        }
        return Response::json(array_reverse($weeks));
    }
}
