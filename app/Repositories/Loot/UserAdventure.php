<?php namespace LootTracker\Repositories\Loot;

use Illuminate\Database\Eloquent\Model;

class UserAdventure extends Model
{
    public static $rules = [
        'adventure_id' => 'required|exists:adventure,id',
        'user_id' => 'required|exists:users,id'
    ];
    protected $table = 'user_adventure';
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo('LootTracker\Repositories\User\User');
    }

    public function adventure()
    {
        return $this->belongsTo('LootTracker\Repositories\Adventure\Adventure');
    }

    public function loot()
    {
        return $this->hasMany('LootTracker\Repositories\Loot\UserAdventureLoot');
    }

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