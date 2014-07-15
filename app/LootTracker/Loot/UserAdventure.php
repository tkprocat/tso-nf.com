<?php
namespace LootTracker\Loot;

class UserAdventure extends \BaseModel
{
    protected $table = 'user_adventure';
    protected $primaryKey = 'id';
    public static $rules = [
        'adventure_id' => 'required|exists:adventure,id',
        'user_id' => 'required|exists:users,id'
    ];

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function adventure()
    {
        return $this->belongsTo('LootTracker\Adventure\Adventure');
    }

    public function loot()
    {
        return $this->hasMany('\LootTracker\Loot\UserAdventureLoot');
    }

    public function getId()
    {
        return $this->ID;
    }

    public function lootText()
    {
        $text = '';
        foreach ($this->loot as $userLoot) {
            if ($text != '')
                $text .= ' / ';
            if ($userLoot->loot->type == 'Nothing')
                $text .= 'Nothing'; //small hack to remove amount.
            else
                $text .= $userLoot->loot->type . ' ' . $userLoot->loot->amount;
        }
        return $text;
    }
}