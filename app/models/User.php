<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * @property mixed email
 * @property mixed password
 * @property mixed remember_token
 * @property mixed guild_id
 */
class User extends \Cartalyst\Sentry\Users\Eloquent\User implements UserInterface, RemindableInterface
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function userAdventure()
    {
        return $this->hasMany('LootTracker\Loot\UserAdventure', 'user_id');
    }

    public function guild()
    {
        if ($this->guild_id > 0) {
            $guild = App::make('LootTracker\Guild\GuildInterface');
            return $guild->findId($this->guild_id);
        }
    }
}