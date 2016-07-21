<?php namespace LootTracker\Test\Integration;

use App;
use LootTracker\Test\TestCase;
use LootTracker\Repositories\Adventure\Admin\AdminAdventureInterface;

class AdminAdventureTest extends TestCase
{

    /**
     * @var $adventureAdminRepo AdminAdventureInterface
     */
    protected $adventureAdminRepo;

    public function setUp()
    {
        parent::setUp();
        $this->loginAsAdmin();
        $this->adventureAdminRepo = App::make(AdminAdventureInterface::class);
    }

    /** @test */
    public function canAddNewAdventure()
    {
        $newAdventure = $this->setupDataForTheBlackKnightsAdventure();
        $this->assertNotNull($newAdventure, 'Something went wrong with saving the adventure!');

        //Test that they got added correctly.
        $this->assertCount(3, $this->adventureAdminRepo->all(), "Adventure haven't been added!");
        $this->assertCount(32, $newAdventure->loot);

        //Check that we can load the create page.
        $this->call('GET', '/admin/adventures/create');
        $this->assertResponseOk();

        //Get the adventure and check the loot has been added.
        $adventure = $this->adventureAdminRepo->findAdventureById($newAdventure->id);
        $this->assertNotNull($adventure);
        //Somewhat redundant, but atleast we're now sure all loot items got saved.
        $this->assertCount(32, $adventure->loot);
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
