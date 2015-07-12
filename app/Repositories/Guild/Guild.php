<?php namespace LootTracker\Repositories\Guild;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use LootTracker\Repositories\User\Role;

/**
 * Class Guild
 * @package LootTracker\Repositories\Guild
 */
class Guild extends Model
{

    /**
     * @var string
     */
    protected $table = 'guilds';

    /**
     * @var array
     */
    protected $fillable = array('name', 'tag');


    /**
     * @return mixed
     */
    public function admins()
    {
        $role = Role::whereName('guild_admin')->first();

        return DB::table('users')->select('users.id', 'users.username')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.id', $role->id)
            ->where('guild_id', $this->id)
            ->orderBy('users.username')->get();
    }


    /**
     * @param $member
     *
     * @return bool
     */
    public function isGuildAdmin($member)
    {
        return (($member->guild_id == $this->id) && $member->hasRole('guild_admin'));
    }


    /**
     * @return mixed
     */
    public function members()
    {
        return $this->hasMany('LootTracker\Repositories\User\User')->orderBy('username')->get();
    }
}
