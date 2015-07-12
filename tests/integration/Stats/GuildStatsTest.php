<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class GuildStatsTest extends TestCase
{

    use DatabaseMigrations;

    protected $statsRepo;

    protected $lootRepo;

    protected $adventureRepo;


    public function setUp()
    {
        parent::setUp();
        $this->login();
        $this->statsRepo     = App::make(LootTracker\Repositories\Stats\GuildStatsInterface::class);
        $this->lootRepo      = App::make(LootTracker\Repositories\Loot\LootInterface::class);
        $this->adventureRepo = App::make(LootTracker\Repositories\Adventure\AdventureInterface::class);
    }


    /** @test */
    public function checkGuildStatsIndex()
    {
        $this->visit('stats/guild');
        $this->assertResponseOk();
    }


    /** @test */
    public function checkPlayedCountForLast30Days()
    {
        $adventure = $this->adventureRepo->byId(1);
        $response  = $this->call('GET', 'stats/guild/getPlayedCountForLast30Days/' . urlencode($adventure->name));
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
    }


    public function tearDown()
    {
        parent::tearDown();
    }


    public function createTestData()
    {
        //Make a Bandit Nest
        $data = [
            'adventure_id' => '1',
            'user_id'      => '2',
            'slot1'        => '1',
            'slot2'        => '7',
            'slot3'        => '9',
            'slot4'        => '13',
            'slot5'        => '17',
            'slot6'        => '21',
            'slot8'        => '27'
        ];
        $this->lootRepo->create($data);

        //Add Black Knights
        $data          = [
            'name'  => 'The Black Knights',
            'slot1' => [
                ['type' => 'Exotic Wood Log', 'amount' => '1400'],
                ['type' => 'Exotic Wood Log', 'amount' => '1600'],
                ['type' => 'Granite', 'amount' => '1100'],
                ['type' => 'Granite', 'amount' => '1300'],
                ['type' => 'Saltpeter', 'amount' => '300'],
                ['type' => 'Saltpeter', 'amount' => '400'],
                ['type' => 'Titanium Ore', 'amount' => '200'],
                ['type' => 'Titanium Ore', 'amount' => '300']
            ],
            'slot2' => [
                ['type' => 'Hardwood Plank', 'amount' => '2000'],
                ['type' => 'Marble', 'amount' => '2000']
            ],
            'slot3' => [
                ['type' => 'Cannon', 'amount' => '150'],
                ['type' => 'Crossbow', 'amount' => '500'],
                ['type' => 'Damascene Sword', 'amount' => '300'],
                ['type' => 'Steel Sword', 'amount' => '800']
            ],
            'slot4' => [
                ['type' => 'Cannon', 'amount' => '150'],
                ['type' => 'Crossbow', 'amount' => '500'],
                ['type' => 'Damascene Sword', 'amount' => '300'],
                ['type' => 'Steel Sword', 'amount' => '800']
            ],
            'slot5' => [
                ['type' => 'Brew', 'amount' => '400'],
                ['type' => 'Bread', 'amount' => '500'],
                ['type' => 'Sausage', 'amount' => '200'],
                ['type' => 'Settler', 'amount' => '400']
            ],
            'slot6' => [
                ['type' => 'Angel Monument', 'amount' => '1'],
                ['type' => 'Dark Castle', 'amount' => '1'],
                ['type' => 'Gold Coin', 'amount' => '300'],
                ['type' => 'Gold Coin', 'amount' => '600'],
                ['type' => 'Wheat Refill', 'amount' => '3000'],
            ],
            'slot7' => [

            ],
            'slot8' => [
                ['type' => 'Exotic Wood Log', 'amount' => '3400'],
                ['type' => 'Granite', 'amount' => '2200'],
                ['type' => 'Saltpeter', 'amount' => '3400'],
                ['type' => 'Titanium Ore', 'amount' => '2060'],
                ['type' => 'Nothing', 'amount' => '1']
            ],
        ];
        $black_knights = $$this->adventureRepo->create($data);

        //Make a Black Knights
        $data = [
            'adventure_id' => $black_knights->id,
            'user_id'      => '2',
            'slot1'        => '1',
            'slot2'        => '7',
            'slot3'        => '9',
            'slot4'        => '13',
            'slot5'        => '17',
            'slot6'        => '21',
            'slot8'        => '27'
        ];
        $this->lootRepo->create($data);
    }
}
