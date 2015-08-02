<?php namespace LootTracker\Test;

use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Repositories\Item\ItemInterface;
use LootTracker\Repositories\Price\Admin\AdminPriceInterface;

class PriceListTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var $priceListAdminRepo \LootTracker\Repositories\Price\Admin\AdminPriceInterface
     */
    protected $priceListAdminRepo;

    /**
     * @var $priceListAdminRepo \LootTracker\Repositories\Item\ItemInterface
     */
    protected $itemRepo;

    public function setUp()
    {
        parent::setUp();
        $this->login();
        $this->itemRepo = App::make(ItemInterface::class);
        $this->priceListAdminRepo = App::make(AdminPriceInterface::class);
    }

    /** @test */
    public function canSeePriceIndexPage()
    {
        $this->visit('/prices')
            ->see('Price list');
    }

    /** @test */
    public function canGetPriceForItemById()
    {
        $item = $this->itemRepo->byId(1);
        $this->assertNotNull($item);
        $this->assertEquals(0.00001, $item->currentPrice->min_price, 'Failure in comparing min_price!');
        $this->assertEquals(0.00002, $item->currentPrice->avg_price, 'Failure in comparing avg_price!');
        $this->assertEquals(0.00003, $item->currentPrice->max_price, 'Failure in comparing max_price!');
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
