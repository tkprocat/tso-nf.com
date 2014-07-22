<?php
namespace LootTracker\Guild;

use Authority\Repo\User\UserInterface;
use Illuminate\Database\Eloquent\Model;

class DbGuildRepository implements GuildInterface
{
    protected $guild;
    public $validator;
    protected $user;
    protected $sentry;

    public function __construct(Model $guild, GuildFormValidator $validator, UserInterface $user, \Cartalyst\Sentry\Sentry $sentry)
    {
        $this->guild = $guild;
        $this->validator = $validator;
        $this->user = $user;
        $this->sentry = $sentry;
    }

    public function all()
    {
        return $this->guild->all();
    }

    public function create($data)
    {
        $guild = new Guild;
        $guild->tag = $data['tag'];
        $guild->name = $data['name'];
        $guild->save();
    }

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

    public function findId($id)
    {
        return $this->guild->findOrFail($id);
    }

    public function findTag($guild_tag)
    {
        return $this->guild->where('tag', $guild_tag)->findOrFail();
    }


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
    }

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
    }

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
        }
    }

    public function removeMember($guild_id, $user_id)
    {
        $user = $this->user->find($user_id);
        $guild = $this->findId($guild_id);

        //Check if we have a rank for the guild otherwise create it.
        $group = \Sentry::findGroupByName('Guild_' . $guild->tag . '_Members');
        $user->removeGroup($group);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function getMembers($id)
    {
        $guild = $this->findId($id);
        $guild_access_members = 'Guild_'.$guild->tag.'_Members';
        $member_group = $this->sentry->findGroupByName($guild_access_members);
        return $this->sentry->findAllUsersInGroup($member_group);
    }

    public function getAdmins($id)
    {
        $guild = $this->findId($id);
        $guild_access_admins = 'Guild_'.$guild->tag.'_Admins';
        $admin_group = $this->sentry->findGroupByName($guild_access_admins);
        return $this->sentry->findAllUsersInGroup($admin_group);
    }
}