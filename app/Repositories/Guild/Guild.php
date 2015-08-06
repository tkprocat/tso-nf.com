<?php namespace LootTracker\Repositories\Guild;

use Cache;
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
        $role_id = Cache::rememberForever('guild_admin_role_id', function() {
            return Role::whereName('guild_admin')->first()->id;
        });

        return $this->hasMany('LootTracker\Repositories\User\User')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.id', $role_id)
            ->where('users.guild_id', $this->id);
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
        return $this->hasMany('LootTracker\Repositories\User\User');
    }

    public function membersCount()
    {
        return $this->hasOne('LootTracker\Repositories\User\User')
            ->selectRaw('guild_id, count(*) as aggregate')
            ->groupBy('guild_id');
    }

    public function getMembersCountAttribute()
    {
        // if relation is not loaded already, let's do it first
        if ( ! array_key_exists('membersCount', $this->relations))
            $this->load('membersCount');

        $related = $this->getRelation('membersCount');

        // then return the count directly
        return ($related) ? (int) $related->aggregate : 0;
    }
}
