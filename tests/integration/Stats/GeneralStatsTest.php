<?php

use Laracasts\TestDummy\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GeneralStatsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->login();
    }

    /** @test */
    public function checkGetLast10Weeks()
    {
        $response = $this->call('GET', 'stats/getLast10Weeks');
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
    }

    /** @test */
    public function checkSubmissionsForTheLast10Weeks()
    {
        $response = $this->call('GET', 'stats/getSubmissionsForTheLast10Weeks');
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
    }

    /** @test */
    public function checkNewUserCountForTheLast10Weeks()
    {
        $response = $this->call('GET', 'stats/getNewUserCountForTheLast10Weeks');
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
    }

    /** @test */
    public function canGetLootTypes()
    {
        $response = $this->call('GET', 'stats/getJSONLootTypes');
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}