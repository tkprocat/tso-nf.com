<?php namespace LootTracker\Http\Controllers;

use DB;
use Response;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Repositories\Stats\GeneralStatsInterface;
use LootTracker\Repositories\User\UserInterface;


class GeneralStatsController extends Controller
{
    protected $layout = 'layouts.default';

    protected $lootRepo;
    protected $adventureRepo;
    protected $statsRepo;
    protected $userRepo;

    function __construct(LootInterface $loot, AdventureInterface $adventure, GeneralStatsInterface $stats, UserInterface $user)
    {
        $this->lootRepo = $loot;
        $this->adventureRepo = $adventure;
        $this->statsRepo = $stats;
        $this->userRepo = $user;
    }

    public function getJSONLootTypes()
    {
        $lootTypes = $this->statsRepo->getLootTypes();
        return Response::json($lootTypes);
    }

    public function getLast10Weeks()
    {
        $weeks = $this->statsRepo->getLast10Weeks();
        return Response::json(array_reverse($weeks));
    }

    public function getLast30Days()
    {
        $days = $this->statsRepo->getLast30Days();
        return Response::json(array_reverse($days));
    }


    public function getSubmissionsForTheLast10Weeks()
    {
        $date = \Carbon\Carbon::now();
        for ($i = 1; $i <= 10; $i++)
        {
            $weeks[] = $this->statsRepo->getSubmissionsForWeek($date);
            $date = $date->subWeek();
        }
        return Response::json(array_reverse($weeks));
    }

    public function getNewUserCountForTheLast10Weeks()
    {
        $date = \Carbon\Carbon::now();
        for ($i = 1; $i <= 10; $i++)
        {
            $weeks[] = $this->statsRepo->getNewUsersForWeek($date);
            $date = $date->subWeek();
        }
        return Response::json(array_reverse($weeks));
    }
}