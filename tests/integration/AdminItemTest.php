<?php namespace LootTracker\Test;

use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Repositories\Item\Admin\AdminItemInterface;
use LootTracker\Repositories\Item\ItemInterface;

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
}
