<?php namespace LootTracker\Http\Controllers;

use Redirect;
use Session;
use LootTracker\Http\Requests\GuildApplicationRequest;
use LootTracker\Repositories\Guild\GuildApplicationInterface;
use LootTracker\Repositories\Guild\GuildInterface;
use LootTracker\Repositories\User\UserInterface;

class GuildApplicationController extends Controller
{

    /**
     * @var GuildInterface
     */
    protected $guildRepo;

    /**
     * @var UserInterface
     */
    protected $userRepo;

    /**
     * @var GuildApplicationInterface
     */
    protected $guildApplicationRepo;

    /**
     * @param GuildInterface $guild
     * @param GuildApplicationInterface $guildApplication
     * @param UserInterface $user
     */
    public function __construct(GuildInterface $guild, GuildApplicationInterface $guildApplication, UserInterface $user)
    {
        $this->guildRepo = $guild;
        $this->guildApplicationRepo = $guildApplication;
        $this->userRepo = $user;
    }


    /**
     * Display a listing of the resource.
     *
     * @param $guild_id
     *
     * @return \Illuminate\View\View
     */
    public function index($guild_id)
    {
        $applications = $this->guildApplicationRepo->all($guild_id);

        return view('applications.index')->with('applications', $applications);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param $guild_id
     *
     * @return \Illuminate\View\View
     */
    public function create($guild_id)
    {
        $guild = $this->guildRepo->byId($guild_id);
        if ($guild != null) {
            return view('guilds.applications.create')->with('guild', $guild);
        } else {
            Session::flash('error', 'No such guild.');
            return Redirect::to('guilds');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param GuildApplicationRequest $request
     *
     * @param                         $guild_id
     *
     * @return Redirect
     */
    public function store(GuildApplicationRequest $request, $guild_id)
    {
        $user = $this->userRepo->getUser();
        $this->guildApplicationRepo->create($request->all(), $user->id, $guild_id);
        return Redirect::to('/guilds')->with(array('success' => 'Your application have been registered.'));
    }


    /**
     * Display the specified resource.
     *
     * @param $guildId
     * @param $applicationId
     *
     * @return \Illuminate\View\View
     */
    public function show($guildId, $applicationId)
    {
        $application = $this->guildApplicationRepo->byId($applicationId);
        return view('guilds.applications.show', compact('application'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $guildId
     * @param $applicationId
     *
     * @return \Illuminate\View\View
     */
    public function destroy($guildId, $applicationId)
    {
        $this->guildApplicationRepo->delete($applicationId);
    }


    /**
     * Approves an user to the guild and removes the application.
     *
     * @param $guildId
     * @param $applicationId
     *
     * @return \Illuminate\View\View
     */
    public function approve($guildId, $applicationId)
    {
        $application = $this->guildApplicationRepo->byId($applicationId);
        $this->guildApplicationRepo->approve($applicationId);
        return Redirect::to('guilds/'.$application->guild->id.'/edit')->with(['success' => 'Member accepted to the guild.']);
    }


    /**
     * Declines the application and removes it.
     *
     * @param $guildId
     * @param $applicationId
     *
     * @return \Illuminate\View\View
     */
    public function decline($guildId, $applicationId)
    {
        $application = $this->guildApplicationRepo->byId($applicationId);
        $this->guildApplicationRepo->decline($applicationId);
        return Redirect::to('guilds/'.$application->guild->id.'/edit')->with(['success' => 'Application declined.']);
    }
}
