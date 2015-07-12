<?php namespace LootTracker\Repositories\User;

use Zizaco\Entrust\EntrustRole;

/**
 * Class Role
 * @package LootTracker\Repositories\User
 */
class Role extends EntrustRole
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany('LootTracker\Repositories\User\Permission');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('LootTracker\Repositories\User\Permission');
    }
}
