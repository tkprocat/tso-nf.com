<?php namespace LootTracker\Repositories\Guild;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use LootTracker\Repositories\User\Role;

class Guild extends Model
{

    protected $table = 'guilds';

    protected $fillable = array('name', 'tag');

    public function admins()
    {
        $role = Role::whereName('guild_admin')->first();

        return DB::table('users')->select('users.id', 'users.username')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.id', $role->id)
            ->where('guild_id', $this->id)->get();
    }

    public function isGuildAdmin($member)
    {
        return (($member->guild_id == $this->id) && $member->hasRole('guild_admin'));
    }

    public function members()
    {
        return $this->hasMany('LootTracker\Repositories\User\User')->get();
    }
}