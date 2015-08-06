<?php namespace LootTracker\Repositories\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * Class User
 * @package LootTracker\Repositories\User
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword, EntrustUserTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userAdventure()
    {
        return $this->hasMany('LootTracker\Repositories\Loot\UserAdventure', 'user_id');
    }

    /**
     * Returns the guild for the current user.
     *
     * @return mixed
     */
    public function guild()
    {
        return $this->hasOne('LootTracker\Repositories\Guild\Guild', 'id', 'guild_id');
    }


    /**
     * Returns the amount of adventures a given player has played.
     *
     * @return mixed
     */
    public function playedCount()
    {
        return $this->hasOne('LootTracker\Repositories\Loot\UserAdventure')
            ->selectRaw('user_id, count(*) as aggregate')
            ->groupBy('user_id');
    }


    /**
     * Helper function for playedCount().
     *
     * @return int
     */
    public function getPlayedCountAttribute()
    {
        if (!$this->relationLoaded('playedCount'))
            $this->load('playedCount');

        $related = $this->getRelation('playedCount');

        return ($related) ? (int) $related->aggregate : 0;
    }
}
