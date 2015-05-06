<?php

class UserTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        Route::enableFilters();
    }

    /** @test */
    public function can_load_users_list()
    {
        $this->call('GET', 'users');
        $this->assertResponseOk();
    }

    /** @test */
    public function can_load_user_page()
    {
        $this->login();

        $user = $this->user->getUser();
        $this->call('GET', 'users/'.$user->id);
        $this->assertResponseOk();
    }

    /** @test */
    public function check_permissions_is_set_correctly()
    {
        //Log in admin and check permissions.
        $user = $this->user->byUsername('admin');
        $this->user->login($user);

        $this->assertTrue($this->user->hasAccess('admin'), 'Admin doesn\'t have admin access!');
        $this->assertTrue($this->user->hasAccess('users'), 'Admin doesn\'t have users access!');

        //Log in admin and check permissions.
        $user = $this->user->byUsername('user1');
        $this->user->login($user);

        $this->assertFalse($this->user->hasAccess('admin'), 'User1 has admin access!');
        $this->assertTrue($this->user->hasAccess('users'), 'User1 doesn\'t have users access!');

        //Log in admin and check permissions.
        $user = $this->user->byUsername('user2');
        $this->user->login($user);

        $this->assertFalse($this->user->hasAccess('admin'), 'User2 has admin access!');
        $this->assertTrue($this->user->hasAccess('users'), 'User2 doesn\'t have users access!');
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}

