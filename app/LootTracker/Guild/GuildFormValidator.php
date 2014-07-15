<?php
namespace LootTracker\Guild;

use LootTracker\Service\Validation\AbstractValidator;

class GuildFormValidator extends AbstractValidator
{
    // Declare the rules for the form validation
    protected $rules = array(
        'name' => 'required',
        'tag' => 'required',
    );

    protected $messages = array(
    );
} 