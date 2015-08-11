<?php namespace LootTracker\Test\Functional;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Test\TestCase;

class GlobalStatsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->login();
    }

    /** @test */
    public function checkGlobalStatsIndex()
    {
        $this->call('GET', 'stats/global');
        $this->assertResponseOk();
    }

    /** @test */
    public function checkTop10BestAdventureForLootTypeByAvgDrop()
    {
        $this->call('GET', 'stats/global/top10bydrop');
        $this->assertResponseOk();

        $response = $this->call('GET', 'stats/global/getTop10BestAdventuresForLootTypeByAvgDrop/Granite');
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
    }

    /** @test */
    public function canSeeSignupRates()
    {
        $this->visit('stats/global/newuserrate')
             ->see('Amount of new users per weeek in the last 10 weeks');
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
