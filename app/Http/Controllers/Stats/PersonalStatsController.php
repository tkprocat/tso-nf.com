<?php namespace LootTracker\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Repositories\Stats\PersonalStatsInterface;
use LootTracker\Repositories\User\UserInterface;

/**
 * Class PersonalStatsController
 * @package LootTracker\Http\Controllers
 */
class PersonalStatsController extends Controller
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
     * @var PersonalStatsInterface
     */
    protected $statsRepo;

    /**
     * @var UserInterface
     */
    protected $userRepo;


    /**
     * @param LootInterface          $loot
     * @param AdventureInterface     $adventure
     * @param PersonalStatsInterface $stats
     * @param UserInterface          $user
     */
    public function __construct(
        LootInterface $loot,
        AdventureInterface $adventure,
        PersonalStatsInterface $stats,
        UserInterface $user
    ) {
        $this->lootRepo      = $loot;
        $this->adventureRepo = $adventure;
        $this->statsRepo     = $stats;
        $this->userRepo      = $user;
    }


    /**
     * @param string $username
     *
     * @return \Illuminate\View\View
     */
    public function getPersonalStats($username = '')
    {
        //If username is blank, we'll override it with the current users.
        if ($username == '') {
            $username = $this->userRepo->getUser()->username;
        }

        $user_id                               = $this->getUserIdFromUsername($username);
        $mostPlayedAdventure                   = $this->statsRepo->getMostPlayedAdventureForUser($user_id);
        $leastPlayedAdventure                  = $this->statsRepo->getLeastPlayedAdventureForUser($user_id);
        $stats['most_played_adventure_name']   = $mostPlayedAdventure['name'];
        $stats['most_played_adventure_count']  = $mostPlayedAdventure['count'];
        $stats['least_played_adventure_name']  = $leastPlayedAdventure['name'];
        $stats['least_played_adventure_count'] = $leastPlayedAdventure['count'];
        $stats['adventures_played_this_week']  = $this->statsRepo->getAdventuresPlayedCountForUserThisWeek($user_id);
        $stats['adventures_played_last_week']  = $this->statsRepo->getAdventuresPlayedCountForUserLastWeek($user_id);

        return view('stats.personal.index', compact('stats', 'username'));
    }


    /**
     * @param string $username
     * @param string $from_date
     * @param string $to_date
     *
     * @return \Illuminate\View\View
     */
    public function getAccumulatedLootBetween($username = '', $from_date = '1970-01-01', $to_date = '2030-31-12')
    {
        $user_id = $this->getUserIdFromUsername($username);

        $accumulatedLoot = $this->statsRepo->getAccumulatedLootForUser($user_id, $from_date, $to_date);

        return view('stats.personal.partials.accumulated_loot', compact('accumulatedLoot'));
    }


    /**
     * @param string $username
     * @param string $date_from
     * @param string $date_to
     *
     * @return \Illuminate\View\View
     */
    public function getAdventuresPlayedBetween($username = '', $date_from = '1970-01-01', $date_to = '2030-31-12')
    {
        $user_id = $this->getUserIdFromUsername($username);

        // Get all played adventures.
        $adventures      = $this->statsRepo->getAdventuresForUserWithPlayed($user_id, $date_from, $date_to)->get();
        $adventures      = $adventures->sortBy(function ($adventure) {
            return $adventure->type . ' - ' . $adventure->name;
        });
        $total_played    = $this->lootRepo->getAllUserAdventuresForUserWithLoot($user_id, $date_from, $date_to)
            ->count();

        $drop_count_list = $this->statsRepo->getLootDropCountForUser($user_id, $date_from, $date_to);

        return view(
            'stats.personal.partials.adventures_played',
            compact('adventures', 'total_played', 'drop_count_list', 'username')
        );
    }


    /**
     * @param $username
     *
     * @return int|\Redirect
     */
    private function getUserIdFromUsername($username)
    {
        if ($username == '') {
            return $this->userRepo->getUser()->id;
        }

        //Get the user id.
        try {
            return $this->userRepo->byUsername($username)->id;
        } catch (ModelNotFoundException $ex) {
            return redirect('/')->withErrors('User not found.');
        }
    }
}
