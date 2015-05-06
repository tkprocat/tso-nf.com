<?php
namespace LootTracker\Guild;

use Authority\Repo\User\UserInterface;
use Cartalyst\Sentry\Sentry;
use Illuminate\Database\Eloquent\Model;

class DbGuildRepository implements GuildInterface
{
    protected $guild;
    public $validator;
    protected $user;
    protected $sentry;

    /**
     * @param Model $guild
     * @param GuildFormValidator $validator
     * @param UserInterface $user
     * @param Sentry $sentry
     */
    public function __construct(Model $guild, GuildFormValidator $validator, UserInterface $user, Sentry $sentry)
    {
        $this->guild = $guild;
        $this->validator = $validator;
        $this->user = $user;
        $this->sentry = $sentry;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->guild->all();
    }

    /**
     * @param $data
     * @param $user_id
     */
    public function create($data, $user_id)
    {
        $user = $this->user->byId($user_id);

        $guild = new Guild;
        $guild->tag = e($data['tag']);
        $guild->name = e($data['name']);
        $guild->save();

        \Sentry::createGroup(array('name' => 'Guild_' . $guild->tag . '_Members'));
        $group = \Sentry::createGroup(array('name' => 'Guild_' . $guild->tag . '_Admins'));
        $user->addGroup($group);

        //Add the user to the guild.
        $this->addMember($guild->id, $user_id);

        //Add response message to the user.
        \Session::flash('success', 'Guild created.');

        return $guild;
    }

    /**
     * @param $data
     */
    public function update($data, $id) {
        $guild = $this->guild->find($id);
        $guild->user_id = e($data['name']);
        $guild->title = e($data['tag']);
        $guild->update();
    }

    /**
     * @param $page
     * @param $guildsPerPage
     * @return \StdClass
     */
    public function findPage($page, $guildsPerPage)
    {
        $result = new \StdClass;
        $result->page = $page;
        $result->limit = $guildsPerPage;
        $result->totalItems = 0;
        $result->items = array();
        $query = $this->guild->orderBy('created_at', 'desc');
        $guild = $query->skip ($guildsPerPage * ($page-1))->take($guildsPerPage)->get();
        $result->items = $guild->all();
        $result->totalItems = $guild->count();
        return $result;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|Model|static
     */
    public function findId($id)
    {
        return $this->guild->findOrFail($id);
    }

    /**
     * @param $guild_tag
     * @return mixed
     */
    public function findTag($guild_tag)
    {
        return $this->guild->where('tag', $guild_tag)->firstOrFail();
    }


    /**
     * @param $guild_id
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function demoteMember($guild_id, $user_id)
    {
        //Get the guild or return an error.
        $guild = $this->guild->find($guild_id);
        if ($guild == null)
            return \Redirect::back()->withErrors('Guild not found.');

        //Check if the user has permission to do this action.
        if (!(\Sentry::hasAccess('admin') || \Sentry::hasAccess('Guild_'.$guild->tag.'_Admins')))
            return \Redirect::back()->withErrors('You do not have sufficient permissions.');

        $guild_access_admins = 'Guild_'.$guild->tag.'_Admins';
        $admin_group = \Sentry::findGroupByName($guild_access_admins);

        $user = \Sentry::findUserById($user_id);
        if ($user == null)
            return \Redirect::back()->withErrors('Guild member not found.');

        $user->removeGroup($admin_group);
        return \Redirect::to('guilds');
    }

    /**
     * @param $guild_id
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function promoteMember($guild_id, $user_id)
    {
        //Get the guild or return an error.
        $guild = $this->guild->find($guild_id);
        if ($guild == null)
            return \Redirect::back()->withErrors('Guild not found.');

        //Check if the user has permission to do this action.
        if (!(\Sentry::hasAccess('admin') || \Sentry::hasAccess('Guild_'.$guild->tag.'_Admins')))
            return \Redirect::back()->withErrors('You do not have sufficient permissions.');

        $guild_access_admins = 'Guild_'.$guild->tag.'_Admins';
        $admin_group = \Sentry::findGroupByName($guild_access_admins);

        $user = \Sentry::findUserById($user_id);
        if ($user == null)
            \Redirect::back()->withErrors('Guild member not found.');

        $user->addGroup($admin_group);
        return \Redirect::to('guilds');
    }

    /**
     * @param $guild_id
     * @param $user_id
     */
    public function addMember($guild_id, $user_id)
    {
        $user = $this->user->byId($user_id);
        //Check if the user is unguilded before adding him to another.
        if ($user->guild_id == 0)
        {
            $guild = $this->findId($guild_id);

            //Check if we have a rank for the guild otherwise create it.
            $group = \Sentry::findGroupByName('Guild_' . $guild->tag . '_Members');
            $user->addGroup($group);
            $user->guild_id = $guild_id;
            $user->save();
        } else {
            \Session::put('error', 'User is already in a guild.');
        }
    }

    /**
     * @param $guild_id
     * @param $user_id
     */
    public function removeMember($guild_id, $user_id)
    {
        $user = $this->user->byId($user_id);
        $guild = $this->findId($guild_id);

        //Check if we have a rank for the guild otherwise create it.
        $group = \Sentry::findGroupByName('Guild_' . $guild->tag . '_Members');
        $user->removeGroup($group);
        $user->guild_id = 0;
        $user->save();
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $guild = $this->guild->findOrFail($id);

        //Remove member and admin ranks.
        $group = \Sentry::findGroupByName('Guild_' . $guild->tag . '_Members');
        $group->delete;

        $group = \Sentry::findGroupByName('Guild_' . $guild->tag . '_Admins');
        $group->delete;

        $guild->delete($id);
    }

    /**
     * @param $id
     * @return array
     */
    public function getMembers($id)
    {
        $guild = $this->findId($id);
        $guild_access_members = 'Guild_'.$guild->tag.'_Members';
        $member_group = $this->sentry->findGroupByName($guild_access_members);
        return $this->sentry->findAllUsersInGroup($member_group);
    }

    /**
     * @param $id
     * @return array
     */
    public function getAdmins($id)
    {
        $guild = $this->findId($id);
        $guild_access_admins = 'Guild_'.$guild->tag.'_Admins';
        $admin_group = $this->sentry->findGroupByName($guild_access_admins);
        return $this->sentry->findAllUsersInGroup($admin_group);
    }

    public function addGuildApplication($guild_id, $user_id)
    {
        GuildApplication::create(array(
            'guild_id' => $guild_id,
            'user_id' => $user_id,
        ));
    }
}