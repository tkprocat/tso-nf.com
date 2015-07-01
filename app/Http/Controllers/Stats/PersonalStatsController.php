<?php namespace LootTracker\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Repositories\Stats\PersonalStatsInterface;
use LootTracker\Repositories\User\UserInterface;


class PersonalStatsController extends Controller
{
    protected $layout = 'layouts.default';

    protected $loot;
    protected $adventure;
    protected $stats;
    protected $user;

    function __construct(LootInterface $loot, AdventureInterface $adventure, PersonalStatsInterface $stats, UserInterface $user)
    {
        $this->loot = $loot;
        $this->adventure = $adventure;
        $this->stats = $stats;
        $this->user = $user;
    }

    public function getPersonalStats($username = '')
    {
        //If username is blank, we'll override it with the current users.
        if ($username == '')
            $username = $this->user->getUser()->username;

        $user_id = $this->getUserIdFromUsername($username);
        $mostPlayedAdventure = $this->stats->getMostPlayedAdventureForUser($user_id);
        $leastPlayedAdventure = $this->stats->getLeastPlayedAdventureForUser($user_id);
        $stats['most_played_adventure_name'] = $mostPlayedAdventure['name'];
        $stats['most_played_adventure_count'] = $mostPlayedAdventure['count'];
        $stats['least_played_adventure_name'] = $leastPlayedAdventure['name'];
        $stats['least_played_adventure_count'] = $leastPlayedAdventure['count'];
        $stats['adventures_played_this_week'] = $this->stats->getAdventuresPlayedCountForUserThisWeek($user_id);
        $stats['adventures_played_last_week'] = $this->stats->getAdventuresPlayedCountForUserLastWeek($user_id);

        return view('stats.personal.index', compact('stats', 'username'));
    }

    public function getAccumulatedLootBetween($username = '', $from_date = '1970-01-01', $to_date = '2030-31-12')
    {
        $user_id = $this->getUserIdFromUsername($username);

        $accumulatedLoot = $this->stats->getAccumulatedLootForUser($user_id, $from_date, $to_date);
        return view('stats.personal.partials.accumulated_loot', compact('accumulatedLoot'));
    }

    public function getAdventuresPlayedBetween($username = '', $date_from = '1970-01-01', $date_to = '2030-31-12')
    {
        $user_id = $this->getUserIdFromUsername($username);

        // Get all played adventures.
        $adventures = $this->stats->getAdventuresForUserWithPlayed($user_id, $date_from, $date_to)->get();
        $adventures = $adventures->sortBy(function($adventure) {
            return $adventure->type. ' - '.$adventure->name;
        });
        $total_played = $this->loot->getAllUserAdventuresForUserWithLoot($user_id, $date_from, $date_to)->count();
        $drop_count_list = $this->stats->getLootDropCountForUser($user_id, $date_from, $date_to);

        return view('stats.personal.partials.adventures_played', compact('adventures', 'total_played', 'drop_count_list', 'username'));
    }

    private function getUserIdFromUsername($username)
    {
        if ($username == '')
            return $this->user->getUser()->id;

        //Get the user id.
        try
        {
            return $this->user->byUsername($username)->id;
        } catch(ModelNotFoundException $ex) {
            return redirect('/')->withErrors('User not found.');
        }
    }
}