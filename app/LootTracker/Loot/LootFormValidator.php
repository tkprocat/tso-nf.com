<?php
namespace LootTracker\Loot;

use LootTracker\Service\Validation\AbstractValidator as AbstractValidator;

class LootFormValidator extends AbstractValidator
{
    // Declare the rules for the form validation
    protected $rules = array(
        'adventure_id' => 'required|exists:adventure,id',
        'user_id' => 'required|exists:users,id'
    );

    protected $messages = array(
        'user_id.exists' => 'That user does not exist!'
    );

    public function updateRules($adventure_id) {
        //Check what slots the given adventure has and add those to the rules.
        $adventureRepo = \App::make('LootTracker\Adventure\AdventureInterface');
        $adventure = $adventureRepo->findAdventureById($adventure_id);
        for($slot = 1; $slot < 9; $slot++) {
            $this->rules = array_except($this->rules, 'slot' . $slot); //Not sure it's needed, but added just in case.
            if ($adventure->loot()->slot($slot)->count() > 0)
                $this->rules = array_add($this->rules, 'slot' . $slot, 'required|exists:adventure_loot,id,slot,'.$slot);
        }
    }
} 