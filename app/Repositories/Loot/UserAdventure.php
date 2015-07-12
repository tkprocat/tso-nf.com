<?php namespace LootTracker\Repositories\Loot;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAdventure
 * @package LootTracker\Repositories\Loot
 */
class UserAdventure extends Model
{

    /**
     * @var array
     */
    public static $rules = [
        'adventure_id' => 'required|exists:adventure,id',
        'user_id' => 'required|exists:users,id'
    ];

    /**
     * @var string
     */
    protected $table = 'user_adventure';

    /**
     * @var string
     */
    protected $primaryKey = 'id';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('LootTracker\Repositories\User\User');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adventure()
    {
        return $this->belongsTo('LootTracker\Repositories\Adventure\Adventure');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loot()
    {
        return $this->hasMany('LootTracker\Repositories\Loot\UserAdventureLoot');
    }


    /**
     * @return string
     */
    public function lootText()
    {
        $text = '';
        //Make sure loots are sorted correctly by slot.
        $loot = $this->loot->sortBy(function ($userLoot) {
            return $userLoot->loot->slot;
        });

        foreach ($loot as $userLoot) {
            if ($text != '') {
                $text .= ' / ';
            }
            if ($userLoot->loot->type == 'Nothing') {
                $text .= 'Nothing';
            } //small hack to remove amount.
            else {
                $text .= $userLoot->loot->type . ' ' . $userLoot->loot->amount;
            }
        }

        return $text;
    }
}
