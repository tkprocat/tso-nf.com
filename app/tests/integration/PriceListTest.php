<?php

use Mockery as m;

class PriceListTest extends TestCase
{
    protected $priceList;
    protected $priceListAdmin;

    public function setUp()
    {
        parent::setUp();
        $this->login();
        Route::enableFilters();
        $this->priceList = App::make('LootTracker\PriceList\PriceListInterface');
        $this->priceListAdmin = App::make('LootTracker\PriceList\Admin\AdminPriceListInterface');
    }

    /** @test */
    public function can_see_price_index_page()
    {
        $this->call('GET', 'prices');
        $this->assertResponseOk();
    }

    /** @test */
    public function can_get_item_list()
    {
        $items = $this->priceList->getAllItems();
        $this->assertCount(1, $items); // One item added by seeder
    }

    /** @test */
    public function can_get_price_for_item_by_id()
    {
        $item = $this->priceList->findItemById(1);
        $this->assertNotNull($item);
        $this->assertEquals(0.00001, $item->price()->first()->min_price, 'Failure in comparing min_price!');
        $this->assertEquals(0.00002, $item->price()->first()->avg_price, 'Failure in comparing avg_price!');
        $this->assertEquals(0.00003, $item->price()->first()->max_price, 'Failure in comparing max_price!');
    }

    /** @test */
    public function can_change_price_for_item()
    {
        //update the prices for item 1
        $this->priceListAdmin->updatePriceForItem(1, 0.00004, 0.00005, 0.00006);

        //Get the item again and see if the price has been updated.
        $item = $this->priceList->findItemById(1);
        $item_price = $item->current_price();
        $this->assertCount(2, $this->priceListAdmin->getAllPriceChangesForItemById(1));
        //var_dump($this->priceListAdmin->getAllPriceChangesForItemById(1));
        //var_dump($item_price);
        $this->assertNotNull($item_price);
        $this->assertEquals(0.00004, $item_price->min_price, 'Failure in comparing min_price after price update!');
        $this->assertEquals(0.00005, $item_price->avg_price, 'Failure in comparing avg_price after price update!');
        $this->assertEquals(0.00006, $item_price->max_price, 'Failure in comparing max_price after price update!');
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }
}