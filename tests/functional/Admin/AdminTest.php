<?php namespace LootTracker\Test\Functional;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Test\TestCase;

class AdminTest extends TestCase
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
        $this->visit('/admin')
            ->see('Dashboard');
    }
}
