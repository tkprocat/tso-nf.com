<?php namespace LootTracker\Repositories\Guild;

/**
 * Class GuildApplication
 * @package LootTracker\Repositories\Guild
 */
class GuildApplication extends \Eloquent
{

    /**
     * @var string
     */
    protected $table = 'guild_applications';

    /**
     * @var array
     */
    protected $fillable = array('guild_id', 'user_id');


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany('User', 'id', 'user_id');
    }
}