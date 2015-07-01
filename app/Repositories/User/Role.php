<?php namespace LootTracker\Repositories\User;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    public function permissions()
    {
        return $this->hasMany('LootTracker\Repositories\User\Permission');
    }

    public function users()
    {
        return $this->hasMany('LootTracker\Repositories\User\Permission');
    }
}