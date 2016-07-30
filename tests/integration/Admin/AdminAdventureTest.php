<?php

namespace LootTracker\Test\Integration;

use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Adventure\Admin\AdminAdventureInterface;
use LootTracker\Repositories\Item\ItemInterface;
use LootTracker\Test\TestCase;

class AdminAdventureTest extends TestCase
{
    use DatabaseMigrations;

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



    /**
     * @return UserAdventure
     */
    private function setupDataForTheBlackKnightsAdventure()
    {
        $itemRepo = App::make(ItemInterface::class);
        $data = array(
            'name' => 'The Black Knights',
            'slot1' => array(
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '1400'),
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '1600'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '1100'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '1300'),
                array('item_id' => $itemRepo->byName('Saltpeter')->id, 'amount' => '300'),
                array('item_id' => $itemRepo->byName('Saltpeter')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '200'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '300'),
            ), 'slot2' => array(
                array('item_id' => $itemRepo->byName('Hardwood Plank')->id, 'amount' => '2000'),
                array('item_id' => $itemRepo->byName('Marble')->id, 'amount' => '2000'),
            ), 'slot3' => array(
                array('item_id' => $itemRepo->byName('Cannon')->id, 'amount' => '150'),
                array('item_id' => $itemRepo->byName('Crossbow')->id, 'amount' => '500'),
                array('item_id' => $itemRepo->byName('Damascene Sword')->id, 'amount' => '300'),
                array('item_id' => $itemRepo->byName('Steel Sword')->id, 'amount' => '800'),
            ), 'slot4' => array(
                array('item_id' => $itemRepo->byName('Cannon')->id, 'amount' => '150'),
                array('item_id' => $itemRepo->byName('Crossbow')->id, 'amount' => '500'),
                array('item_id' => $itemRepo->byName('Damascene Sword')->id, 'amount' => '300'),
                array('item_id' => $itemRepo->byName('Steel Sword')->id, 'amount' => '800'),
            ), 'slot5' => array(
                array('item_id' => $itemRepo->byName('Brew')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Bread')->id, 'amount' => '500'),
                array('item_id' => $itemRepo->byName('Sausage')->id, 'amount' => '200'),
                array('item_id' => $itemRepo->byName('Settler')->id, 'amount' => '400'),
            ), 'slot6' => array(
                array('item_id' => $itemRepo->byName('Angel Monument')->id, 'amount' => '1'),
                array('item_id' => $itemRepo->byName('Dark Castle')->id, 'amount' => '1'),
                array('item_id' => $itemRepo->byName('Gold Coin')->id, 'amount' => '300'),
                array('item_id' => $itemRepo->byName('Gold Coin')->id, 'amount' => '600'),
                array('item_id' => $itemRepo->byName('Wheat Refill')->id, 'amount' => '3000'),
            ), 'slot8' => array(
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '3400'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '2200'),
                array('item_id' => $itemRepo->byName('Saltpeter')->id, 'amount' => '3400'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '2060'),
                array('item_id' => $itemRepo->byName('Nothing')->id, 'amount' => '1')
            )
        );
        $adventureRepo = App::make(AdventureInterface::class);
        return $adventureRepo->create($data);
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
