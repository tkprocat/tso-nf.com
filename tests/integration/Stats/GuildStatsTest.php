<?php namespace LootTracker\Test;

use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use \LootTracker\Repositories\Adventure\AdventureInterface;

class GuildStatsTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @var $adventureRepo \LootTracker\Repositories\Adventure\AdventureInterface
     */
    protected $adventureRepo;


    public function setUp()
    {
        parent::setUp();
        $this->login();
        $this->adventureRepo = App::make(AdventureInterface::class);
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
}
