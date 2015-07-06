<?php namespace LootTracker\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Input;
use Redirect;
use LootTracker\Http\Requests\CreateGuildRequest;
use LootTracker\Http\Requests\UpdateGuildRequest;
use LootTracker\Repositories\Guild\GuildInterface;
use LootTracker\Repositories\Guild\UserAlreadyInAGuildException;
use LootTracker\Repositories\User\UserInterface;

class GuildController extends Controller
{

    protected $guild;
    protected $user;

    public function __construct(GuildInterface $guild, UserInterface $user)
    {
        $this->guild = $guild;
        $this->user = $user;
        $this->user->redirectNonAuthedUser();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $guilds = $this->guild->paginate(25);
        return view('guilds.index')->with('guilds', $guilds);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('guilds.create');
    }

    /**
     * Stores a new guild after validation passes.
     *
     * @param CreateGuildRequest $request
     * @return Response
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
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $guild = $this->guild->byId($id);
        $admins = $this->guild->getAdmins($id);
        $members = $this->guild->getMembers($id);
        return view('guilds.show', compact('guild', 'admins', 'members'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //Check if the guild exists.
        $guild = $this->getGuildFromId($id, 'Guild not found.');

        if (!($this->user->isAdmin() || ($this->user->getUser()->can('admin-guild') && $this->isUserInGuild($id, $this->user->getUser()->id)))) {
            return Redirect::to('/')->with(array('error' => 'Sorry, you do not have the necessary permissions to edit this guild.'));
        }

        return view('guilds.edit')->with('guild', $guild);
    }

    /**
     * Stores an updated guild after validation passes.
     *
     * @param UpdateGuildRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(UpdateGuildRequest $request, $id)
    {
        //Check if the guild exists.
        $this->getGuildFromId($id, 'Guild not found.');

        $this->guild->update($request->all(), $id);

        return Redirect::to('guilds/'.$id)->with(array('success' => 'Guild updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $guild_id
     * @return Response
     */
    public function destroy($guild_id)
    {
        //Check if the user has permission to do this action.
        $user = $this->user->getUser();
        if (!($this->user->isAdmin() || ($user->can('admin-guild-members') && $this->isUserInGuild($guild_id, $user->id)))) {
            return Redirect::back()->with(array('error' => 'You do not have sufficient permissions.'));
        }

        //Check if the guild exists.
        $this->getGuildFromId($guild_id, 'Guild not found.');

        //Delete the guild
        $this->guild->delete($guild_id);
        return Redirect::to('/guilds')->with(array('success' => 'Guild disbanded.'));
    }

    public function addMember($guild_id, $user_id)
    {
        //Check that the user we're trying to add exists.
        $user = $this->getUserFromId($user_id, 'User not found.');

        //Check if the guild exists.
        $this->getGuildFromId($guild_id, 'Guild not found.');

        //Check if the user has permission to do this action.
        if (!($this->user->isAdmin() || ($this->user->getUser()->can('admin-guild-members') && $this->isUserInGuild($guild_id, $user_id)))) {
            return Redirect::back()->with(array('error' => 'You do not have sufficient permissions.'));
        }

        try {
            $this->guild->addMember($guild_id, $user->id);
        } catch(UserAlreadyInAGuildException $ex) {
            return Redirect::back()->withErrors($ex->getMessage());
        }
        return Redirect::back()->with(array('success' => 'User added to guild.'));
    }

    public function addMemberPost($guild_id)
    {
        //Check that the user we're trying to add exists.
        try {
            $user = $this->user->byUsername(Input::get('username'));
        } catch(ModelNotFoundException $ex)
        {
            return Redirect::back()->with(array('error' => 'User not found.'));
        }
        return $this->addMember($guild_id, $user->id);
    }

    public function removeMember($guild_id, $user_id)
    {
        //Check that the user we're trying to remove exists.
        $user = $this->getUserFromId($user_id, 'Guild member not found.');

        //Check if the user has permission to do this action.
        if (!($this->user->isAdmin() || ($this->user->getUser()->can('admin-guild-members') && $this->isUserInGuild($guild_id, $user_id)))) {
            return Redirect::back()->with(array('error' => 'You do not have sufficient permissions.'));
        }

        //Check the person is in the guild.
        if (intval($user->guild_id) !== intval($guild_id)) {
            return Redirect::back()->with(array('error' => 'That user is not a member of the guild.'));
        }

        $this->guild->removeMember($user->id);
        return Redirect::back()->with(array('success' => 'Guild member kicked.'));
    }

    public function promoteMember($guild_id, $user_id)
    {
        //Check that the user we're trying to promote exists.
        $user = $this->getUserFromId($user_id, 'Guild member not found.');

        //Check if the guild exists.
        $this->getGuildFromId($guild_id, 'Guild not found.');

        //Check the person is in the guild already.
        if (intval($user->guild_id) !== intval($guild_id)) {
            return Redirect::back()->with(array('error' => 'That user is not a member of the guild.'));
        }

        //Check if the user has permission to do this action.
        if (!($this->user->isAdmin() || ($this->user->getUser()->can('admin-guild-members') && $this->isUserInGuild($guild_id, $user_id)))) {
            return Redirect::back()->with(array('error' => 'You do not have sufficient permissions.'));
        }

        $this->guild->promoteMember($user_id);

        return Redirect::back()->with(array('success' => 'Member promoted.'));
    }

    public function demoteMember($guild_id, $user_id)
    {
        //Check that the user we're trying to promote exists.
        $user = $this->getUserFromId($user_id, 'Guild member not found.');

        //Check if the guild exists.
        $guild = $this->getGuildFromId($guild_id, 'Guild not found.');

        //Check the person is in the guild already.
        if (intval($user->guild_id) !== intval($guild_id)) {
            return Redirect::back()->with(array('error' => 'That user is not a member of the guild.'));
        }

        //Check if the user has permission to do this action.
        if (!($this->user->isAdmin() || ($this->user->getUser()->can('admin-guild-members') && $this->isUserInGuild($guild_id, $user_id)))) {
            return Redirect::back()->with(array('error' => 'You do not have sufficient permissions.'));
        }

        //Check if we have more then one admin left in the guild after the demote.
        if (count($guild->admins()) == 1) {
            return Redirect::back()->with(array('error' => 'You can not demote the last admin in the guild, either promote a new one or disband the guild.'));
        }


        $this->guild->demoteMember($user_id);
        return Redirect::back()->with(array('success' => 'Member demoted.'));
    }

    private function isUserInGuild($guild_id, $user_id)
    {
        $user = $this->getUserFromId($user_id, 'User not found.');
        //Always return true if the user has admin role.
        if ($user->hasRole('admin'))
            return true;

        //If the current user is not admin, check if the user is in the guild.
        return ($this->user->getUser()->guild_id === $guild_id);
    }

    /**
     * @param $guild_id
     * @return mixed
     */
    private function getGuildFromId($guild_id, $message, $redirect_to = '')
    {
        //Get the guild or return an error.
        try {
            return $this->guild->byId($guild_id);
        } catch (ModelNotFoundException $ex) {
            if ($redirect_to !== '')
                return Redirect::to($redirect_to)->with(array('error' => $message));
            else
                return Redirect::back()->with(array('error' => $message));
        }
    }

    /**
     * @param int $user_id
     * @param string $message
     * @param string $redirect_to
     * @return mixed
     */
    private function getUserFromId($user_id, $message, $redirect_to = '')
    {
        //Get the guild or return an error.
        try {
            return $this->user->byId($user_id);
        } catch (ModelNotFoundException $ex) {
            if ($redirect_to !== '')
                return Redirect::to($redirect_to)->with(array('error' => $message));
            else
                return Redirect::back()->with(array('error' => $message));
        }
    }
}
