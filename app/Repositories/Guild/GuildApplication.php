<?php namespace LootTracker\Repositories\Guild;

use LootTracker\Repositories\Guild\Guild;
use LootTracker\Repositories\User\User;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guild()
    {
        return $this->belongsTo(Guild::class);
    }
}