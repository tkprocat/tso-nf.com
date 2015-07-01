<?php
namespace LootTracker\Repositories\Guild;

use Auth;
use Entrust;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use LootTracker\Repositories\User\Role;
use LootTracker\Repositories\User\UserInterface;

class EloquentGuildRepository implements GuildInterface
{
    protected $user;

    /**
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Guild::all();
    }

    /**
     * @param $itemsPerPage
     * @return mixed
     */
    public function paginate($itemsPerPage)
    {
        return Guild::orderBy('name')->paginate($itemsPerPage);
    }

    /**
     * @param $data
     * @param $user_id
     * @return Guild
     */
    public function create($data, $user_id)
    {
        //Create the guild
        $guild = new Guild;
        $guild->tag = e($data['tag']);
        $guild->name = e($data['name']);
        $guild->save();

        //Attach roles to the user creating the guild.
        $user = $this->user->byId($user_id);
        //check if the user has the role before addding them.
        if (!$user->hasRole('guild_admin'))
          $user->roles()->attach($this->getAdminRole());
        if (!$user->hasRole('guild_member'))
          $user->roles()->attach($this->getMemberRole());

        //Add the user to the guild.
        $this->addMember($guild->id, $user->id);

        return $guild;
    }

    /**
     * @param $data
     * @param $id
     */
    public function update($data, $id)
    {
        $guild = $this->byId($id);
        $guild->name = e($data['name']);
        $guild->tag = e($data['tag']);
        $guild->update();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function byId($id)
    {
        return Guild::findOrFail($id);
    }

    /**
     * @param $guild_tag
     * @return mixed
     */
    public function byTag($guild_tag)
    {
        return Guild::whereTag($guild_tag)->firstOrFail();
    }

    /**
     * @param $guild_id
     * @param $user_id
     * @return mixed
     */
    public function demoteMember($guild_id, $user_id)
    {
        //Get the guild or return an error.
        $guild = $this->byId($guild_id);
        if ($guild == null) {
            return Redirect::back()->withErrors('Guild not found.');
        }

        //Check if the user has permission to do this action.
        if (!(Entrust::hasRole('admin') || Entrust::can('can_admin_guild_members'))) {
            return Redirect::back()->withErrors('You do not have sufficient permissions.');
        }

        $user = $this->user->byId($user_id);
        if ($user == null) {
            return Redirect::back()->withErrors('Guild member not found.');
        }

        $user->detachRole($this->getAdminRole());

        return Redirect::to('guilds/' . $guild_id);
    }

    /**
     * @param $guild_id
     * @param $user_id
     * @return mixed
     */
    public function promoteMember($guild_id, $user_id)
    {
        $user = $this->user->byId($user_id);
        $user->attachRole($this->getAdminRole());
    }

    /**
     * Assign a user membership to a guild.
     *
     * @param $guild_id
     * @param $user_id
     * @return mixed
     */
    public function addMember($guild_id, $user_id)
    {
        $guild = $this->byId($guild_id);
        if ($guild == null) {
            return Redirect::back()->withErrors('Guild not found.');
        }

        //Check if the user has permission to do this action.
        $user = $this->user->byId(Auth::user()->id);
        if (!($user->hasRole('admin') || $user->can('admin-guild-members'))) {
            return Redirect::back()->withErrors('You do not have sufficient permissions.');
        }

        $user = $this->user->byId($user_id);
        //Check if the user is unguilded before adding him to another.
        if ($user->guild_id == 0) {
            //Check if we have a rank for the guild otherwise create it.
            if (!$user->hasRole('guild_member'))
                $user->roles()->attach($this->getMemberRole());
            $user->guild_id = $guild_id;
            $user->save();
        } else {
            Session::put('error', 'User is already in a guild.');
        }
    }

    /**
     * @param $guild_id
     * @param $user_id
     * @return mixed
     */
    public function removeMember($guild_id, $user_id)
    {
        $guild = $this->byId($guild_id);
        if ($guild == null) {
            return Redirect::back()->withErrors('Guild not found.');
        }

        //Check if the user has permission to do this action.
        if (!(Entrust::hasRole('admin') || Entrust::can('can_admin_guild_members'))) {
            return Redirect::back()->withErrors('You do not have sufficient permissions.');
        }

        $user = $this->user->byId($user_id);
        //Remove any guild related roles.
        $user->detachRole($this->getMemberRole());
        $user->detachRole($this->getAdminRole());

        //Remove the guild reference
        $user->guild_id = 0;
        $user->save();
    }

    /**
     * @param $id
     */
    public function delete($guild_id)
    {
        $guild = $this->byId($guild_id);
        //Check if the user has permission to do this action.
        if (!(Entrust::hasRole('admin') || Entrust::can('can_admin_guild'))) {
            return Redirect::back()->withErrors('You do not have sufficient permissions.');
        }

        foreach ($this->getMembers($guild->id) as $member) {
            $this->removeMember($guild->id, $member->id);
        }

        foreach ($this->getAdmins($guild->id) as $admin) {
            $this->removeMember($guild->id, $admin->id);
        }

        $guild->delete($guild_id);
    }

    /**
     * @param $guild_id
     * @return mixed
     */
    public function getMembers($guild_id)
    {
        $guild = $this->byId($guild_id);

        return $guild->members();
    }

    /**
     * @param $guild_id
     * @return mixed
     */
    public function getAdmins($guild_id)
    {
        $guild = $this->byId($guild_id);

        return $guild->admins();
    }

    /**
     * @param $guild_id
     * @param $user_id
     */
    public function addGuildApplication($guild_id, $user_id)
    {
        GuildApplication::create(array(
            'guild_id' => $guild_id,
            'user_id' => $user_id,
        ));
    }

    /**
     * @return mixed
     */
    protected function getMemberRole()
    {
        return Role::whereName('guild_member')->first();
    }

    /**
     * @return mixed
     */
    protected function getAdminRole()
    {
        return Role::whereName('guild_admin')->first();
    }
}