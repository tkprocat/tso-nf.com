<?php
use Laracasts\TestDummy\Factory;

class StatsTest extends TestCase
{
    protected $stats_repo;
    protected $loot_repo;
    public function setUp()
    {
        parent::setUp();
        $user = Sentry::findUserByLogin('admin');
        Sentry::login($user);
        $this->stats_repo = App::make('LootTracker\Stats\StatsInterface');
        $this->loot_repo = App::make('LootTracker\Loot\LootInterface');
    }

    /** @test */
    public function check_stat_global_index()
    {
        $this->call('GET', 'stats/global');
        $this->assertResponseOk();
    }

    /** @test */
    public function check_personal_stats_for_user()
    {
        //1 loot should be registered from seeding the database.
        $this->assertCount(1, $this->loot_repo->findAllAdventuresForUser(2)->get());

        //We need to add some more data...
        $this->create_test_data();

        $this->call('GET', 'stats/personal/admin');
        $this->assertResponseOk();

        //And without username, it should automatically take the current users stats, e.g. stats for admin.
        $this->call('GET', '/stats/personal');
        $this->assertResponseOk();

        //Check amount of adventures played.
        $result = $this->stats_repo->getAdventuresForUserWithPlayed(2, '', '')->get();
        $this->assertCount(2, $result, 'Wrong numbers of adventures returned from getAdventuresForUserWithPlayed when called without dates.');

        //Check amount of adventures played with dates.
        $result = $this->stats_repo->getAdventuresForUserWithPlayed(2, '2014-01-01', '2030-01-01')->get();
        $this->assertCount(2, $result, 'Wrong numbers of adventures returned from getAdventuresForUserWithPlayed when called with dates.');

        $result = $this->stats_repo->getMostPlayedAdventureForUser(2);
        $this->assertEquals('Bandit Nest', $result['name'], 'Most played adventure mismatch.');
        $this->assertEquals(2, $result['count'], 'Most played adventure count mismatch.');

        $result = $this->stats_repo->getLeastPlayedAdventureForUser(2);
        $this->assertEquals('The Black Knights', $result['name'], 'Least played adventure mismatch.');
        $this->assertEquals(1, $result['count'], 'Least played adventure count mismatch.');
		
		$adventures_played_this_week = $this->stats_repo->getAdventuresPlayedCountForUserThisWeek(2);
		$this->assertEquals(3, $adventures_played_this_week, 'Mismatch in numbers of adventures played this week.');
		
		//This is a fairly week and lazy test...
		$adventures_played_last_week = $this->stats_repo->getAdventuresPlayedCountForUserLastWeek(2);
		$this->assertEquals(0, $adventures_played_last_week, 'Mismatch in numbers of adventures played last week.');
	}

    /** @test */
    public function check_played_adventure_for_user()
    {
        $this->call('GET', '/stats/adventuresplayed/user1/2013-01-01/2030-12-31');
        $this->assertResponseOk();
    }

    /** @test */
    public function check_get_last_10_weeks()
    {
        $response = $this->call('GET', 'stats/getLast10Weeks');
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
    }

    /** @test */
    public function check_submissions_for_the_last_10_weeks()
    {
        $response = $this->call('GET', 'stats/getSubmissionsForTheLast10Weeks');
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
    }

    /** @test */
    public function check_new_user_count_for_the_last_10_weeks()
    {
        $response = $this->call('GET', 'stats/getNewUserCountForTheLast10Weeks');
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
    }

    /** @test */
    public function check_top_10_best_adventure_for_loot_type_by_avg_drop()
    {
        $this->call('GET', 'stats/top10bydrop');
        $this->assertResponseOk();
        //Currently does not work on SQLite
        //$response = $this->call('GET', 'stats/getTop10BestAdventuresForLootTypeByAvgDrop/Granite');
        //$this->assertResponseOk();
        ///$this->assertJson($response->getContent());
    }

    /** @test */
    public function check_top_10_best_adventures_for_loot_by_drop_chance()
    {
        $this->call('GET', 'stats/top10bydropchance');
        $this->assertResponseOk();
        //Currently does not work on SQLite
        //$response = $this->call('GET', 'stats/getTop10BestAdventuresForLootTypeByDropChance/Granite');
        //$this->assertResponseOk();
        //$this->assertJson($response->getContent());
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function create_test_data()
    {
        $loot_repo = App::make('LootTracker\Loot\LootInterface');
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
        $this->loot_repo->validator->updateRules($data['adventure_id']);
        if (!$this->loot_repo->validator->with($data)->passes())
            dd($this->loot_repo->validator->errors());
        $loot_repo->create($data);

        //Add Black Knights
		$adventure_repo = App::make('LootTracker\Adventure\AdventureInterface');
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
        $black_knights = $adventure_repo->create($data);
		
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
		//TODO: Fix this issue!
        $this->loot_repo->validator->updateRules($data['adventure_id']);
        if (!$this->loot_repo->validator->with($data)->passes())
            dd($this->loot_repo->validator->errors());
        $loot_repo->create($data);
    }
	
}