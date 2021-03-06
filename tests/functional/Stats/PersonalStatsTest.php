<?php namespace LootTracker\Test\Functional;

use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Repositories\Stats\PersonalStatsInterface;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Test\TestCase;

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
        $this->assertCount(3, $this->lootRepo->findAllAdventuresForUser(2)->get());

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
        $this->assertEquals(2, $adventures_played_this_week, 'Mismatch in numbers of adventures played this week.');

        $adventures_played_last_week = $this->statsRepo->getAdventuresPlayedCountForUserLastWeek(2);
        $this->assertEquals(1, $adventures_played_last_week, 'Mismatch in numbers of adventures played last week.');
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

    public function tearDown()
    {
        parent::tearDown();
    }
}
