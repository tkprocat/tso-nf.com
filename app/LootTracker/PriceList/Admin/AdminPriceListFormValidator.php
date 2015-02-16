<?php
namespace LootTracker\PriceList\Admin;

use LootTracker\Service\Validation\AbstractValidator as AbstractValidator;

class AdminPriceListFormValidator extends AbstractValidator
{
    // Declare the rules for the form validation
    protected $rules = array(
        'name' => 'required|alpha_spaces',
    );

    protected $messages = array(
    );
}