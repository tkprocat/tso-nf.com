<?php namespace LootTracker\Repositories\Guild;

use Exception;
use LootTracker\Repositories\User\Role;
use LootTracker\Repositories\User\UserInterface;

/**
 * Class EloquentGuildRepository
 * @package LootTracker\Repositories\Guild
 */
class EloquentGuildRepository implements GuildInterface
{

    /**
     * @var UserInterface
     */
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
        //check if the user has the role before adding them.
        if (!$user->hasRole('guild_admin')) {
            $user->roles()->attach($this->getAdminRole());
        }
        if (!$user->hasRole('guild_member')) {
            $user->roles()->attach($this->getMemberRole());
        }

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
     * @param $user_id
     * @return mixed
     */
    public function demoteMember($user_id)
    {
        $user = $this->user->byId($user_id);
        $user->detachRole($this->getAdminRole());
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function promoteMember($user_id)
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
     * @throws Exception
     */
    public function addMember($guild_id, $user_id)
    {
        $user = $this->user->byId($user_id);
        //Check if the user is unguilded before adding him to another.
        if ($user->guild_id == 0) {
            //Check if we have a rank for the guild otherwise create it.
            if (!$user->hasRole('guild_member')) {
                $user->roles()->attach($this->getMemberRole());
            }
            $user->guild_id = $guild_id;
            $user->save();
        } else {
            throw new UserAlreadyInAGuildException("User is already in a guild!");
        }
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function removeMember($user_id)
    {
        $user = $this->user->byId($user_id);
        //Remove any guild related roles.
        $user->detachRole($this->getMemberRole());
        $user->detachRole($this->getAdminRole());

        //Remove the guild reference
        $user->guild_id = 0;
        $user->save();
    }

    /**
     * @param $guild_id
     * @internal param $id
     */
    public function delete($guild_id)
    {
        $guild = $this->byId($guild_id);

        foreach ($this->getMembers($guild->id) as $member) {
            $this->removeMember($member->id);
        }

        foreach ($this->getAdmins($guild->id) as $admin) {
            $this->removeMember($admin->id);
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
/**
 * Class UserAlreadyInAGuildException
 * @package LootTracker\Repositories\Guild
 */
class UserAlreadyInAGuildException extends Exception {}
