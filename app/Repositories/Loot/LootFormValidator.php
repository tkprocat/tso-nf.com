<?php namespace LootTracker\Repositories\Loot;

use LootTracker\Repositories\Validation\AbstractValidator as AbstractValidator;

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

    public function updateRules($adventure_id)
    {
        //Check what slots the given adventure has and add those to the rules.
        $adventureRepo = \App::make('LootTracker\Repositories\Adventure\AdventureInterface');
        $adventure = $adventureRepo->byId($adventure_id);
        for ($slot = 1; $slot < 30; $slot++) {
            $this->rules = array_except($this->rules, 'slot' . $slot); //Not sure it's needed, but added just in case.
            if ($adventure->loot()->slot($slot)->count() > 0) {
                $this->rules = array_add($this->rules, 'slot' . $slot,
                    'required|exists:adventure_loot,id,slot,' . $slot);
            }
        }
    }
} 