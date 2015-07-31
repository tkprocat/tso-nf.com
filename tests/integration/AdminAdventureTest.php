<?php namespace LootTracker\Test;

use App;
use LootTracker\Repositories\Adventure\Admin\AdminAdventureInterface;

class AdminAdventureTest extends TestCase
{

    /**
     * @var $adventureRepo AdminAdventureInterface
     */
    protected $adventureRepo;

    public function setUp()
    {
        parent::setUp();
        $this->loginAsAdmin();
        $this->adventureRepo = App::make(AdminAdventureInterface::class);
    }

    /** @test */
    public function canAddNewAdventure()
    {
        $newAdventure = $this->setupDataForTheBlackKnightsAdventure();
        $this->assertNotNull($newAdventure, 'Something went wrong with saving the adventure!');

        //Test that they got added correctly.
        $this->assertCount(2, $this->adventureRepo->all(), "Adventure haven't been added!");
        $this->assertCount(32, $newAdventure->loot);

        //Check that we can load the create page.
        $this->call('GET', '/admin/adventures/create');
        $this->assertResponseOk();

        //Get the adventure and check the loot has been added.
        $adventure = $this->adventureRepo->findAdventureById($newAdventure->id);
        $this->assertNotNull($adventure);
        //Somewhat redundant, but atleast we're now sure all loot items got saved.
        $this->assertCount(32, $adventure->loot);
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
