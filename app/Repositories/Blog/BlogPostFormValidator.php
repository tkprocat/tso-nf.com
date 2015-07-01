<?php
namespace LootTracker\Repositories\Blog;

use LootTracker\Repositories\Validation\AbstractValidator as AbstractValidator;

class BlogPostFormValidator extends AbstractValidator
{
    // Declare the rules for the form validation
    protected $rules = array(
        'title' => 'required',
        'content' => 'required',
        'user_id' => 'required|exists:users,id'
    );

    protected $messages = array(
        'user_id.exists' => 'That user does not exist!'
    );
} 