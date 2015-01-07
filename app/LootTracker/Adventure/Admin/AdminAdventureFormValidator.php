<?php
namespace LootTracker\Adventure\Admin;

use LootTracker\Service\Validation\AbstractValidator as AbstractValidator;

class AdminAdventureFormValidator extends AbstractValidator
{
    // Declare the rules for the form validation
    protected $rules = array(
        'name' => 'required|alpha_spaces',
    );

    protected $messages = array(
    );

    public function updateRules($data) {
        return;
        //add dynamic rules.
        for($i = 1;$i < count($data); $i++)
        {
            //Only check if one of the fields are filled out
            if (($data[$i]['slot'] != '') || ($data[$i]['type'] != '') || ($data[$i]['amount'] != '')) {
                $this->rules = array_add($this->rules, "items.$i.slot", 'required|integer');
                //$this->rules = array_add($this->rules, "items.$i.type", 'required|alpha_spaces');
                $this->rules = array_add($this->rules, "items.$i.amount", 'required|integer');
            }
        }
    }
}