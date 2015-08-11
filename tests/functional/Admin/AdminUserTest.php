<?php namespace LootTracker\Test\Functional;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Test\TestCase;

class AdminUserTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->login('admin');
    }

    /** @test */
    public function canSeeDashboard()
    {
        $this->visit('/admin/users')
            ->see('users')
            ->see('user1');
    }

    /** @test */
    public function canEditUser()
    {
        $this->visit('/admin/users/1/edit')
            ->see('Account Profile')
            ->see('admin')
            ->see('admin@tso-nf.com');
    }
}
