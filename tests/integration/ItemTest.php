<?php namespace LootTracker\Test;

use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Repositories\Item\Admin\AdminItemInterface;
use LootTracker\Repositories\Item\ItemInterface;

class ItemTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var $itemRepo \LootTracker\Repositories\Item\ItemInterface
     */
    protected $itemRepo;

    public function setUp()
    {
        parent::setUp();
        $this->login('admin');
        $this->itemRepo = App::make(ItemInterface::class);
    }

    /** @test */
    public function canGetItemList()
    {
        $items = $this->itemRepo->all();
        $this->assertCount(27, $items); // One item added by seeder
    }
}
