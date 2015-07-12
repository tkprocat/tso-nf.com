<?php namespace LootTracker\Http\Controllers;

use Input;
use Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use LootTracker\Http\Requests\CreateGuildRequest;
use LootTracker\Http\Requests\UpdateGuildRequest;
use LootTracker\Repositories\Guild\GuildInterface;
use LootTracker\Repositories\Guild\UserAlreadyInAGuildException;
use LootTracker\Repositories\User\UserInterface;

/**
 * Class GuildController
 * @package LootTracker\Http\Controllers
 */
class GuildController extends Controller
{

    /**
     * @var GuildInterface
     */
    protected $guild;

    /**
     * @var UserInterface
     */
    protected $user;


    /**
     * @param GuildInterface $guild
     * @param UserInterface  $user
     */
    public function __construct(GuildInterface $guild, UserInterface $user)
    {
        $this->guild = $guild;
        $this->user  = $user;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $guilds = $this->guild->paginate(25);

        return view('guilds.index')->with('guilds', $guilds);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('guilds.create');
    }


    /**
     * Stores a new guild after validation passes.
     *
     * @param CreateGuildRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function store(CreateGuildRequest $request)
    {
        $user_id = $this->user->getUser()->id;
        $this->guild->create($request->all(), $user_id);

        return Redirect::to('guilds')->with('success', 'Guild created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int $guild_id
     *
     * @return \Illuminate\View\View
     */
    public function show($guild_id)
    {
        $guild   = $this->guild->byId($guild_id);
        $admins  = $this->guild->getAdmins($guild_id);
        $members = $this->guild->getMembers($guild_id);

        return view('guilds.show', compact('guild', 'admins', 'members'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $guild_id
     *
     * @return \Illuminate\View\View
     */
    public function edit($guild_id)
    {
        //Check if the guild exists.
        $guild = $this->getGuildFromId($guild_id, 'Guild not found.');

        if (!$this->isUserGuildAdmin($guild_id)) {
            return Redirect::back()->with(['error' => 'You do not have sufficient permissions.']);
        }

        return view('guilds.edit')->with('guild', $guild);
    }


    /**
     * Stores an updated guild after validation passes.
     *
     * @param UpdateGuildRequest $request
     * @param int                $id
     *
     * @return \Illuminate\View\View
     */
    public function update(UpdateGuildRequest $request, $id)
    {
        //Check if the guild exists.
        $this->getGuildFromId($id, 'Guild not found.');

        $this->guild->update($request->all(), $id);

        return Redirect::to('guilds/' . $id)->with(['success' => 'Guild updated successfully.']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $guild_id
     *
     * @return \Illuminate\View\View
     */
    public function destroy($guild_id)
    {
        //Check if the user has permission to do this action.
        if (!$this->isUserGuildAdmin($guild_id)) {
            return Redirect::back()->with(['error' => 'You do not have sufficient permissions.']);
        }

        //Check if the guild exists.
        $this->getGuildFromId($guild_id, 'Guild not found.');

        //Delete the guild
        $this->guild->delete($guild_id);

        return Redirect::to('/guilds')->with(['success' => 'Guild disbanded.']);
    }


    /**
     * @param int   $guild_id
     * @param int   $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addMember($guild_id, $user_id)
    {
        //Check that the user we're trying to add exists.
        $user = $this->getUserFromId($user_id, 'User not found.');

        //Check if the guild exists.
        $this->getGuildFromId($guild_id, 'Guild not found.');

        //Check if the user has permission to do this action.
        if (!$this->isUserGuildAdmin($guild_id)) {
            return Redirect::back()->with(['error' => 'You do not have sufficient permissions.']);
        }

        try {
            $this->guild->addMember($guild_id, $user->id);
        } catch (UserAlreadyInAGuildException $ex) {
            return Redirect::back()->withErrors($ex->getMessage());
        }

        return Redirect::back()->with(['success' => 'User added to guild.']);
    }


    /**
     * @param int   $guild_id
     *
     * @return \Illuminate\Http\RedirectResponse|GuildController
     */
    public function addMemberPost($guild_id)
    {
        //Check that the user we're trying to add exists.
        try {
            $user = $this->user->byUsername(Input::get('username'));
        } catch (ModelNotFoundException $ex) {
            return Redirect::back()->with(['error' => 'User not found.']);
        }

        return $this->addMember($guild_id, $user->id);
    }


    /**
     * @param int   $guild_id
     * @param int   $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeMember($guild_id, $user_id)
    {
        //Check that the user we're trying to remove exists.
        $user = $this->getUserFromId($user_id, 'Guild member not found.');

        //Check if the user has permission to do this action.
        if (!$this->isUserGuildAdmin($guild_id)) {
            return Redirect::back()->with(['error' => 'You do not have sufficient permissions.']);
        }

        $this->guild->removeMember($user->id);

        return Redirect::back()->with(['success' => 'Guild member kicked.']);
    }


    /**
     * @param int   $guild_id
     * @param int   $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function promoteMember($guild_id, $user_id)
    {
        //Check that the user we're trying to promote exists.
        $user = $this->getUserFromId($user_id, 'Guild member not found.');

        //Check if the guild exists.
        $this->getGuildFromId($guild_id, 'Guild not found.');

        //Check if the user has permission to do this action.
        if (!$this->isUserGuildAdmin($guild_id)) {
            return Redirect::back()->with(['error' => 'You do not have sufficient permissions.']);
        }

        //Check that the user we're trying to promote is actually in the guild.
        if (!$this->isUserInGuild($guild_id, $user_id)) {
            return Redirect::back()->with(array('error' => 'That user is not a member of the guild.'));
        }

        $this->guild->promoteMember($user->id);

        return Redirect::back()->with(['success' => 'Member promoted.']);
    }


    /**
     * @param int   $guild_id
     * @param int   $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function demoteMember($guild_id, $user_id)
    {
        //Check that the user we're trying to promote exists.
        $user = $this->getUserFromId($user_id, 'Guild member not found.');

        //Check if the guild exists.
        $guild = $this->getGuildFromId($guild_id, 'Guild not found.');

        //Check if the user has permission to do this action.
        if (!$this->isUserGuildAdmin($guild_id)) {
            return Redirect::back()->with(['error' => 'You do not have sufficient permissions.']);
        }

        //Check that the user we're trying to promote is actually in the guild.
        if (!$this->isUserInGuild($guild_id, $user_id)) {
            return Redirect::back()->with(array('error' => 'That user is not a member of the guild.'));
        }

        //Check if we have more then one admin left in the guild after the demote.
        if (count($guild->admins()) == 1) {
            return Redirect::back()->with([
                'error' => 'You can not demote the last admin in the guild, either promote a new one or disband the guild.'
            ]);
        }

        $this->guild->demoteMember($user->id);

        return Redirect::back()->with(['success' => 'Member demoted.']);
    }


    /**
     * @param int   $guild_id
     * @param int   $user_id
     *
     * @return bool
     */
    private function isUserInGuild($guild_id, $user_id)
    {
        $user = $this->getUserFromId($user_id, 'User not found.');
        //Always return true if the user has admin role.
        if ($user->hasRole('admin')) {
            return true;
        }

        //If the current user is not admin, check if the user is in the guild.
        return ( intval($user->guild_id) === intval($guild_id) );
    }


    /**
     * @param int    $guild_id
     * @param string $message
     * @param string $redirect_to
     *
     * @return mixed
     */
    private function getGuildFromId($guild_id, $message, $redirect_to = '')
    {
        //Get the guild or return an error.
        try {
            return $this->guild->byId($guild_id);
        } catch (ModelNotFoundException $ex) {
            if ($redirect_to !== '') {
                return Redirect::to($redirect_to)->with(['error' => $message]);
            } else {
                return Redirect::back()->with(['error' => $message]);
            }
        }
    }


    /**
     * @param int    $user_id
     * @param string $message
     * @param string $redirect_to
     *
     * @return mixed
     */
    private function getUserFromId($user_id, $message, $redirect_to = '')
    {
        //Get the guild or return an error.
        try {
            return $this->user->byId($user_id);
        } catch (ModelNotFoundException $ex) {
            if ($redirect_to !== '') {
                return Redirect::to($redirect_to)->with(['error' => $message]);
            } else {
                return Redirect::back()->with(['error' => $message]);
            }
        }
    }


    /**
     * @param int   $guild_id
     *
     * @return bool
     */
    private function isUserGuildAdmin($guild_id)
    {
        //Admins are considered guild admins for all guilds.
        if ($this->user->isAdmin()) {
            return true;
        }

        //If the user are not in the guild, return false.
        if (!$this->isUserInGuild($guild_id, $this->user->getUser()->id)) {
            return false;
        }

        //We've established that the user is in the guild, now check for permissions.
        if ($this->user->getUser()->can('admin-guild-members')) {
            return true;
        }

        return false;
    }
}
