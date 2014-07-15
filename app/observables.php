<?php

// User Login event
Event::listen('user.login', function($userId, $username)
{
    Session::put('userId', $userId);
    Session::put('username', $username);
});

// User logout event
Event::listen('user.logout', function()
{
	Session::flush();
});

// Subscribe to User Mailer events
Event::subscribe('Authority\Mailers\UserMailer');