<?php namespace LootTracker\Test\Functional;

use App;
use LootTracker\Repositories\Adventure\Admin\AdminAdventureInterface;
use LootTracker\Test\TestCase;

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
    public function canLoadAdventureList()
    {
        $this->visit('admin/adventures')
            ->see('Adventures');
    }

    /** @test */
    public function canSeeDetailsForAdventure()
    {
        $this->visit('admin/adventures/1')
            ->see('Adventure: Bandit Nest');
    }

    /** @test */
    public function canCreateNewAdventure()
    {
        $this->visit('admin/adventures/create')
            ->see('New adventure');

        //Not really how I wanted it, but selecting an array field in type() seems to be problematic to say the least.
        $form  = [
            'name' => 'Test adventure',
            'type' => 'Mini',
            'items[1][slot]' => '1',
            'items[1][itemid]' => '1',
            'items[1][amount]' => '100'
        ];
        //Check the adventure got created.
        $this->visit('admin/adventures/create')->submitForm('Add adventure', $form)
            ->seeInDatabase('adventure', ['name' => 'Test adventure', 'type' => 'Mini']);

        //Get the adventure and see if the loot item got created.
        $adventure = $this->adventureRepo->findAdventureByName('Test adventure');
        $this->seeInDatabase('adventure_loot', ['adventure_id' => $adventure->id, 'slot' => 1, 'item_id' => 1, 'amount' => 100]);
    }

    /** @test */
    public function canEditAdventure()
    {
        $this->visit('admin/adventures/1/edit')
            ->see('Bandit Nest')
            ->type('Bandit Camp', 'name')
            ->type('Test', 'type')
            ->press('Update')
            ->seeInDatabase('adventure', ['name' => 'Bandit Camp', 'type' => 'Test']);
    }

    /** @test */
    public function failsEditingNonExistingAdventure()
    {
        $this->visit('admin/adventures/1000/edit')
            ->seePageIs('admin/adventures')
            ->see('Adventure not found!');
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
