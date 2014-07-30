<?php
namespace LootTracker\Guild;

use Cartalyst\Sentry\Groups\GroupNotFoundException;

class Guild extends \Eloquent {

	protected $table = 'guilds';

    protected $fillable = array('name', 'tag');

    public function save(array $options = array()) {
        //Check if we have the ranks for the guild already, otherwise create them.
        //Admins
        try {
            \Sentry::findGroupByName('Guild_' . $this['tag'] . '_Admins');
        } catch (GroupNotFoundException $e) {
            \Sentry::createGroup(array('name' => 'Guild_' . $this['tag'] . '_Admins', 'permissions' => array('guild_' . $this['id'] . '_admin' => 1)));
        }

        //Members
        try {
            \Sentry::findGroupByName('Guild_' . $this['tag'] . '_Members');
        } catch (GroupNotFoundException $e) {
            \Sentry::createGroup(array('name' => 'Guild_' . $this['tag'] . '_Members', 'permissions' => array('guild_' . $this['id'] . '_member' => 1)));
        }

        return parent::save($options);
    }

    public function members() {
        return $this->hasMany('User', 'guild_id');
    }

    public function isGuildAdmin($member) {
        $group = \Sentry::findGroupByName('Guild_'.$this->tag.'_Admins');
        return $member->inGroup($group);
    }
}