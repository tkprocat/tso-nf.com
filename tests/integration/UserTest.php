<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function canLoadUsersList()
    {
        $this->login();
        $this->visit('/users');
    }

    /** @test */
    public function canLoadUserPage()
    {
        $this->login();

        $user = $this->user->getUser();
        $this->visit('users/'.$user->username);
    }

    /** @test */
    public function checkPermissionsIsSetCorrectly()
    {
        //Log in admin and check permissions.
        $user = $this->user->byUsername('admin');
        $this->user->login($user);

        $this->assertTrue($this->user->getUser()->hasRole('admin'), 'Admin does not have admin access!');
        $this->assertTrue($this->user->getUser()->hasRole('user'), 'Admin does not have users access!');

        //Log in admin and check permissions.
        $user = $this->user->byUsername('user1');
        $this->user->login($user);

        $this->assertFalse($this->user->getUser()->can('admin-blog'), 'User1 can admin blogs!');
        $this->assertFalse($this->user->getUser()->hasRole('admin'), 'User1 has admin access!');
        $this->assertTrue($this->user->getUser()->hasRole('user'), 'User1 does not have users access!');

        //Log in admin and check permissions.
        $user = $this->user->byUsername('user2');
        $this->user->login($user);

        $this->assertFalse($this->user->getUser()->hasRole('admin'), 'User2 has admin access!');
        $this->assertTrue($this->user->getUser()->hasRole('user'), 'User2 does not have users access!');
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}

