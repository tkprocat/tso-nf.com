<?php
namespace LootTracker\Guild;

use Cartalyst\Sentry\Groups\GroupNotFoundException;

class Guild extends \Eloquent {

	protected $table = 'guilds';

    protected $fillable = array('name', 'tag');

    public function members() {
        return $this->hasMany('User', 'guild_id');
    }

    public function isGuildAdmin($member) {
        $group = \Sentry::findGroupByName('Guild_'.$this->tag.'_Admins');
        return $member->inGroup($group);
    }

    public function admins()
    {
        $group = \Sentry::findGroupByName('Guild_'.$this->tag.'_Admins');
        return \DB::table('users')->select('username')
                  ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
                  ->join('groups', 'groups.id', '=', 'users_groups.group_id')
                  ->where('groups.id', $group->getId())->get();
    }
}