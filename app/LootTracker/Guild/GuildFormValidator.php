<?php
namespace LootTracker\Guild;

use LootTracker\Service\Validation\AbstractValidator;

class GuildFormValidator extends AbstractValidator
{
    // Declare the rules for the form validation
    protected $rules = array(
        'name' => 'required|unique:guilds',
        'tag' => 'required|unique:guilds',
    );

    protected $messages = array(
    );
}
