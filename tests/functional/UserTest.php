<?php namespace LootTracker\Test\Functional;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Test\TestCase;

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

        $user = $this->userRepo->getUser();
        $this->visit('users/'.$user->username);
    }

    /** @test */
    public function checkPermissionsIsSetCorrectly()
    {
        //Log in admin and check permissions.
        $user = $this->userRepo->byUsername('admin');
        $this->userRepo->login($user);

        $this->assertTrue($this->userRepo->getUser()->hasRole('admin'), 'Admin does not have admin access!');
        $this->assertTrue($this->userRepo->getUser()->hasRole('user'), 'Admin does not have users access!');

        //Log in admin and check permissions.
        $user = $this->userRepo->byUsername('user1');
        $this->userRepo->login($user);

        $this->assertFalse($this->userRepo->getUser()->can('admin-blog'), 'User1 can admin blogs!');
        $this->assertFalse($this->userRepo->getUser()->hasRole('admin'), 'User1 has admin access!');
        $this->assertTrue($this->userRepo->getUser()->hasRole('user'), 'User1 does not have users access!');

        //Log in admin and check permissions.
        $user = $this->userRepo->byUsername('user2');
        $this->userRepo->login($user);

        $this->assertFalse($this->userRepo->getUser()->hasRole('admin'), 'User2 has admin access!');
        $this->assertTrue($this->userRepo->getUser()->hasRole('user'), 'User2 does not have users access!');
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
