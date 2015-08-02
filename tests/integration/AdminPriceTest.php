<?php namespace LootTracker\Test;

use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Repositories\Item\ItemInterface;
use LootTracker\Repositories\Price\Admin\AdminPriceInterface;

class AdminPriceTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var $itemRepo \LootTracker\Repositories\Item\ItemInterface
     */
    protected $itemRepo;

    /**
     * @var $itemAdminRepo \LootTracker\Repositories\Price\Admin\AdminPriceInterface
     */
    protected $priceAdminRepo;

    public function setUp()
    {
        parent::setUp();
        $this->login('admin');
        $this->itemRepo = App::make(ItemInterface::class);
        $this->priceAdminRepo = App::make(AdminPriceInterface::class);
    }

    /** @test */
    public function canSeePricesPage()
    {
        $this->visit('/admin/prices')
            ->see('Prices');
    }

    /** @test */
    public function canChangePriceForItem()
    {
        $this->visit('/admin/prices/1/edit')
            ->type('0.00004', 'min_price')
            ->type('0.00005', 'avg_price')
            ->type('0.00006', 'max_price')
            ->press('Update')
            ->seePageIs('/admin/prices')
            ->see('Prices updated.');


        //Get the item again and see if the price has been updated.
        $item = $this->itemRepo->byId(1);
        $this->assertCount(2, $this->priceAdminRepo->findAllPriceChangesForItemById(1));
        $this->assertNotNull($item);
        $this->assertEquals(0.00004, $item->currentPrice->min_price, 'Failure in comparing min_price after price update!');
        $this->assertEquals(0.00005, $item->currentPrice->avg_price, 'Failure in comparing avg_price after price update!');
        $this->assertEquals(0.00006, $item->currentPrice->max_price, 'Failure in comparing max_price after price update!');
    }
}
