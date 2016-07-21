<?php namespace LootTracker\Test\Functional;

use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Repositories\Item\ItemInterface;
use LootTracker\Test\TestCase;

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
        $this->assertCount(38, $items);
    }
}
