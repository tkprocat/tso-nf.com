<?php
namespace LootTracker\Blog;

use LootTracker\Service\Validation\AbstractValidator as AbstractValidator;

class BlogCommentFormValidator extends AbstractValidator
{
    // Declare the rules for the form validation
    protected $rules = array(
        'content' => 'required',
        'user_id' => 'required|exists:users,id'
    );

    protected $messages = array(
        'user_id.exists' => 'That user does not exist!'
    );
} 