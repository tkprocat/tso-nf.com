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
}