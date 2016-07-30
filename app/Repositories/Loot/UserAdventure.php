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
     * @return UserAdventureLoot
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
            if ($userLoot->loot->item->name == 'Nothing') {
                $text .= 'Nothing';
            } //small hack to remove amount.
            else {
                $text .= $userLoot->loot->item->name . ' ' . $userLoot->loot->amount;
            }
        }

        return $text;
    }


    /**
     * Runs through all items and calculates an estimated price based on each items avg. market price.
     *
     * @return int
     */
    public function getEstimatedLootValue()
    {
        $value = 0;
        foreach($this->loot as $userAdventureLoot)
        {
            $value += $userAdventureLoot->loot->item->currentPrice->avg_price * $userAdventureLoot->loot->amount;
        }
        return round($value, 2);
    }
}
