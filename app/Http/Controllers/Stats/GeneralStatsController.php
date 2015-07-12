<?php namespace LootTracker\Http\Controllers;

use Response;
use Carbon\Carbon;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Repositories\Stats\GeneralStatsInterface;
use LootTracker\Repositories\User\UserInterface;

/**
 * Class GeneralStatsController
 * @package LootTracker\Http\Controllers
 */
class GeneralStatsController extends Controller
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
     * @var GeneralStatsInterface
     */
    protected $statsRepo;

    /**
     * @var UserInterface
     */
    protected $userRepo;


    /**
     * @param LootInterface         $loot
     * @param AdventureInterface    $adventure
     * @param GeneralStatsInterface $stats
     * @param UserInterface         $user
     */
    public function __construct(
        LootInterface $loot,
        AdventureInterface $adventure,
        GeneralStatsInterface $stats,
        UserInterface $user
    ) {
        $this->lootRepo = $loot;
        $this->adventureRepo = $adventure;
        $this->statsRepo = $stats;
        $this->userRepo = $user;
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJSONLootTypes()
    {
        $lootTypes = $this->statsRepo->getLootTypes();
        return Response::json($lootTypes);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLast10Weeks()
    {
        $weeks = $this->statsRepo->getLast10Weeks();
        return Response::json(array_reverse($weeks));
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLast30Days()
    {
        $days = $this->statsRepo->getLast30Days();
        return Response::json(array_reverse($days));
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubmissionsForTheLast10Weeks()
    {
        $date = Carbon::now();
        $weeks = [];
        for ($i = 1; $i <= 10; $i++) {
            $weeks[] = $this->statsRepo->getSubmissionsForWeek($date);
            $date = $date->subWeek();
        }
        return Response::json(array_reverse($weeks));
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNewUserCountForTheLast10Weeks()
    {
        $date = Carbon::now();
        $weeks = [];
        for ($i = 1; $i <= 10; $i++) {
            $weeks[] = $this->statsRepo->getNewUsersForWeek($date);
            $date = $date->subWeek();
        }
        return Response::json(array_reverse($weeks));
    }
}
