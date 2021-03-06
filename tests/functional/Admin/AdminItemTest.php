<?php namespace LootTracker\Test\Functional;

use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Repositories\Item\Admin\AdminItemInterface;
use LootTracker\Repositories\Item\ItemInterface;
use LootTracker\Test\TestCase;

class AdminItemTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var $itemRepo \LootTracker\Repositories\Item\ItemInterface
     */
    protected $itemRepo;

    /**
     * @var $itemAdminRepo \LootTracker\Repositories\Item\Admin\AdminItemInterface
     */
    protected $itemAdminRepo;

    public function setUp()
    {
        parent::setUp();
        $this->login('admin');
        $this->itemRepo = App::make(ItemInterface::class);
        $this->itemAdminRepo = App::make(AdminItemInterface::class);
    }

    /** @test */
    public function canSeeItemsPage()
    {
        $this->visit('/admin/items')
            ->see('Items');
    }

    /** @test */
    public function canAddNewItem()
    {
        $this->visit('/admin/items/create')
            ->see('Add item')
            ->type('Iron Ore', 'name')
            ->type('Resource', 'category')
            ->press('Add')
            ->seeInDatabase('items', ['name' => 'Iron Ore', 'category' => 'Resource']);
    }

    /** @test */
    public function canAddNewItemWithPrices()
    {
        $this->visit('/admin/items/create')
            ->see('Add item')
            ->type('Iron Ore', 'name')
            ->type('Resource', 'category')
            ->type('0.011', 'min_price')
            ->type('0.022', 'avg_price')
            ->type('0.033', 'max_price')
            ->press('Add')
            ->seeInDatabase('items', ['name' => 'Iron Ore', 'category' => 'Resource'])
            ->seeInDatabase('prices', ['min_price' => '0.011', 'avg_price' => '0.022', 'max_price' => '0.033']);
    }

    /** @test */
    public function canEditItem()
    {
        $this->visit('/admin/items/1/edit')
            ->see('Update item')
            ->type('Test name', 'name')
            ->type('Test category', 'category')
            ->press('Update')
            ->seeInDatabase('items', ['name' => 'Test name', 'category' => 'Test category']);
    }

    /** @test */
    public function canSeeItemDetails()
    {
        $this->visit('/admin/items/1')
            ->see('Item: Coal')
            ->see('Category: Resource')
            ->see('Created at:')
            ->see('Last updated at:');
    }

    /** @test */
    public function failsSeeingItemWithInvalidID()
    {
        $this->visit('/admin/items/99999')
            ->seePageIs('/admin/items')
            ->see('Item not found!');
    }

    /** @test */
    public function failsEditingItemWithInvalidID()
    {
        $this->visit('/admin/items/99999/edit')
            ->seePageIs('/admin/items')
            ->see('Item not found!');
    }
}
