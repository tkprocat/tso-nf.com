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
        $this->assertEquals(0.00001, $item->price()->first()->min_price, 'Failure in compaing min_price!');
        $this->assertEquals(0.00002, $item->price()->first()->avg_price, 'Failure in compaing avg_price!');
        $this->assertEquals(0.00003, $item->price()->first()->max_price, 'Failure in compaing max_price!');
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }
}