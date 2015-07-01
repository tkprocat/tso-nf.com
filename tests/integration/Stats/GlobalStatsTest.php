<?php

use Laracasts\TestDummy\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GlobalStatsTest extends TestCase
{
    use DatabaseMigrations;

    protected $statsRepo;
    protected $lootRepo;
    public function setUp()
    {
        parent::setUp();
        $this->login();
        $this->statsRepo = App::make('LootTracker\Repositories\Stats\GlobalStatsInterface');
        $this->lootRepo = App::make('LootTracker\Repositories\Loot\LootInterface');
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

    public function tearDown()
    {
        parent::tearDown();
    }

    public function createTestData()
    {
        $lootRepo = App::make('LootTracker\Repositories\Loot\LootInterface');
        //Make a Bandit Nest
        $data = array(
            'adventure_id' => '1',
            'user_id' => '2',
            'slot1' => '1',
            'slot2' => '7',
            'slot3' => '9',
            'slot4' => '13',
            'slot5' => '17',
            'slot6' => '21',
            'slot8' => '27'
        );
        $lootRepo->create($data);

        //Add Black Knights
		$adventureRepo = App::make('LootTracker\Repositories\Adventure\AdventureInterface');
		   $data = array(
            'name' => 'The Black Knights',
            'slot1' => array(
                array('type' => 'Exotic Wood Log', 'amount' => '1400'),
                array('type' => 'Exotic Wood Log', 'amount' => '1600'),
                array('type' => 'Granite', 'amount' => '1100'),
                array('type' => 'Granite', 'amount' => '1300'),
                array('type' => 'Saltpeter', 'amount' => '300'),
                array('type' => 'Saltpeter', 'amount' => '400'),
                array('type' => 'Titanium Ore', 'amount' => '200'),
                array('type' => 'Titanium Ore', 'amount' => '300')
            ),
            'slot2' => array(
                array('type' => 'Hardwood Plank', 'amount' => '2000'),
                array('type' => 'Marble', 'amount' => '2000')
            ),
            'slot3' => array(
                array('type' => 'Cannon', 'amount' => '150'),
                array('type' => 'Crossbow', 'amount' => '500'),
                array('type' => 'Damascene Sword', 'amount' => '300'),
                array('type' => 'Steel Sword', 'amount' => '800')
            ),
            'slot4' => array(
                array('type' => 'Cannon', 'amount' => '150'),
                array('type' => 'Crossbow', 'amount' => '500'),
                array('type' => 'Damascene Sword', 'amount' => '300'),
                array('type' => 'Steel Sword', 'amount' => '800')
            ),
            'slot5' => array(
                array('type' => 'Brew', 'amount' => '400'),
                array('type' => 'Bread', 'amount' => '500'),
                array('type' => 'Sausage', 'amount' => '200'),
                array('type' => 'Settler', 'amount' => '400')
            ),
            'slot6' => array(
                array('type' => 'Angel Monument', 'amount' => '1'),
                array('type' => 'Dark Castle', 'amount' => '1'),
                array('type' => 'Gold Coin', 'amount' => '300'),
                array('type' => 'Gold Coin', 'amount' => '600'),
                array('type' => 'Wheat Refill', 'amount' => '3000'),
            ),
            'slot7' => array(

            ),
            'slot8' => array(
                array('type' => 'Exotic Wood Log', 'amount' => '3400'),
                array('type' => 'Granite', 'amount' => '2200'),
                array('type' => 'Saltpeter', 'amount' => '3400'),
                array('type' => 'Titanium Ore', 'amount' => '2060'),
                array('type' => 'Nothing', 'amount' => '1')
            ),
        );
        $black_knights = $adventureRepo->create($data);
		
		//Make a Black Knights
        $data = array(
            'adventure_id' => $black_knights->id,
            'user_id' => '2',
            'slot1' => '1',
            'slot2' => '7',
            'slot3' => '9',
            'slot4' => '13',
            'slot5' => '17',
            'slot6' => '21',
            'slot8' => '27'
        );
        $lootRepo->create($data);
    }
	
}