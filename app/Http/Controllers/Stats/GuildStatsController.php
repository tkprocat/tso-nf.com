<?php namespace LootTracker\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Response;
use LootTracker\Repositories\Stats\GuildStatsInterface;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Guild\GuildInterface;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Repositories\User\UserInterface;

class GuildStatsController extends Controller
{
    protected $layout = 'layouts.default';

    protected $loot;
    protected $adventure;
    protected $stats;
    protected $user;
    protected $guild;


    /**
     * @param LootInterface       $loot
     * @param AdventureInterface  $adventure
     * @param GuildStatsInterface $stats
     * @param UserInterface       $user
     * @param GuildInterface      $guild
     */
    public function __construct(
        LootInterface $loot,
        AdventureInterface $adventure,
        GuildStatsInterface $stats,
        UserInterface $user,
        GuildInterface $guild
    ) {
        $this->loot = $loot;
        $this->adventure = $adventure;
        $this->stats = $stats;
        $this->user = $user;
        $this->guild = $guild;
    }

    public function index()
    {
        $guild_id = $this->user->getUser()->guild_id;

        // Get all played adventures.
        $adventures = Collection::make($this->stats->getAdventuresWithPlayedAndLoot($guild_id));
        $adventures = $adventures->sortBy(function ($adventure) {
            return $adventure->type . ' - ' . $adventure->name;
        });
        $total_played = $this->stats->getPlayedCount($guild_id);
        $drop_count_list = $this->stats->getLootDropCount($guild_id);
        return view('stats.guild.index', compact('adventures', 'total_played', 'drop_count_list'));
    }


    /**
     * @param $adventureName
     *
     * @return \Illuminate\View\View
     */
    public function show($adventureName)
    {
        $guild_id = $this->user->getUser()->guild_id;

        // Find adventure
        $tempAdventure = $this->adventure->byName(urldecode($adventureName));


        // Get all played adventures.
        $adventure = Collection::make($this->stats->getAdventuresWithPlayedAndLoot($guild_id, $tempAdventure->id))
            ->first();

        $total_played = $this->stats->getPlayedCount($guild_id, $tempAdventure->id);
        $drop_count_list = $this->stats->getLootDropCount($guild_id, $tempAdventure->id);
        $adventureLoot = $tempAdventure->loot->toArray();
        //Merge adventure loot with drop count.
        array_walk($adventureLoot, function (&$value, $key) use ($drop_count_list, $total_played) {
            if (array_has($drop_count_list, $value['id'])) {
                $value['dropped'] = $drop_count_list[$value['id']];
                $value['dropped_percentage'] = number_format($drop_count_list[$value['id']] / $total_played * 100);
            } else {
                $value['dropped'] = 0;
                $value['dropped_percentage'] = 0;
            }
        });

        return view('stats.guild.show', compact('adventure', 'total_played', 'adventureLoot'));
    }

    public function showSubmissionRate()
    {
        return view('stats.guild.submissionrate');
    }


    /**
     * @param $adventureName
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlayedCountForLast30Days($adventureName)
    {
        // Find adventure
        $guild_id = $this->user->getUser()->guild_id;
        $adventure = $this->adventure->byName(urldecode($adventureName));

        $date = Carbon::now();
        $weeks = [];
        for ($i = 1; $i <= 30; $i++) {
            $weeks[] = $this->stats->getPlayedCountPerDayForAdventure($guild_id, $date, $adventure->id);
            $date = $date->subDay();
        }
        return Response::json(array_reverse($weeks));
    }
}
