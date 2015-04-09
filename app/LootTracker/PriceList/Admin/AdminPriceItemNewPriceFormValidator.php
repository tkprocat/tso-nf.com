<?php
namespace LootTracker\PriceList\Admin;

use LootTracker\Service\Validation\AbstractValidator as AbstractValidator;

class AdminPriceItemNewPriceFormValidator extends AbstractValidator
{
    // Declare the rules for the form validation
    protected $rules = array(
        'min_price' => 'required',
        'avg_price' => 'required',
        'max_price' => 'required'
    );

    protected $messages = array(
    );
}