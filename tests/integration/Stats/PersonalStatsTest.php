<?php namespace LootTracker\Test;

use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use \LootTracker\Repositories\Adventure\AdventureInterface;
use \LootTracker\Repositories\Stats\PersonalStatsInterface;
use \LootTracker\Repositories\Loot\LootInterface;

class PersonalStatsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var $statsRepo \LootTracker\Repositories\Stats\PersonalStatsInterface
     */
    protected $statsRepo;

    /**
     * @var $lootRepo \LootTracker\Repositories\Loot\LootInterface
     */
    protected $lootRepo;

    public function setUp()
    {
        parent::setUp();
        $this->login();
        $this->statsRepo = App::make(PersonalStatsInterface::class);
        $this->lootRepo = App::make(LootInterface::class);
    }

    /** @test */
    public function checkPersonalStatsForUser()
    {
        //1 loot should be registered from seeding the database.
        $this->assertCount(1, $this->lootRepo->findAllAdventuresForUser(2)->get());

        //We need to add some more data...
        $this->createTestData();

        //Check we can load personal stats for the user admin.
        $this->call('GET', 'stats/personal/admin');
        $this->assertResponseOk();

        //And without username, it should automatically take the current users stats, e.g. stats for admin.
        $this->call('GET', '/stats/personal');
        $this->assertResponseOk();

        //Check amount of adventures played.
        $result = $this->statsRepo->getAdventuresForUserWithPlayed(2, '', '')->get();
        $this->assertCount(2, $result, 'Wrong numbers of adventures returned from getAdventuresForUserWithPlayed '.
            'when called without dates.');

        //Check amount of adventures played with dates.
        $result = $this->statsRepo->getAdventuresForUserWithPlayed(2, '2014-01-01', '2030-01-01')->get();
        $this->assertCount(2, $result, 'Wrong numbers of adventures returned from getAdventuresForUserWithPlayed '.
            'when called with dates.');

        $result = $this->statsRepo->getMostPlayedAdventureForUser(2);
        $this->assertEquals('Bandit Nest', $result['name'], 'Most played adventure mismatch.');
        $this->assertEquals(2, $result['count'], 'Most played adventure count mismatch.');

        $result = $this->statsRepo->getLeastPlayedAdventureForUser(2);
        $this->assertEquals('The Black Knights', $result['name'], 'Least played adventure mismatch.');
        $this->assertEquals(1, $result['count'], 'Least played adventure count mismatch.');

        $adventures_played_this_week = $this->statsRepo->getAdventuresPlayedCountForUserThisWeek(2);
        $this->assertEquals(3, $adventures_played_this_week, 'Mismatch in numbers of adventures played this week.');

        //This is a fairly week and lazy test...
        $adventures_played_last_week = $this->statsRepo->getAdventuresPlayedCountForUserLastWeek(2);
        $this->assertEquals(0, $adventures_played_last_week, 'Mismatch in numbers of adventures played last week.');
    }

    /** @test */
    public function checkAccumulatedLootForUser()
    {
        $this->call('GET', '/stats/personal/accumulatedloot/user1/2013-01-01/2030-12-31');
        $this->assertResponseOk();
    }

    /** @test */
    public function checkPlayedAdventureForUser()
    {
        $this->call('GET', '/stats/personal/adventuresplayed/user1/2013-01-01/2030-12-31');
        $this->assertResponseOk();
    }

    public function createTestData()
    {
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
        $this->lootRepo->create($data);

        //Add Black Knights
        $adventureRepo = App::make(AdventureInterface::class);
        $data = [
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
        ];
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
        $this->lootRepo->create($data);
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
