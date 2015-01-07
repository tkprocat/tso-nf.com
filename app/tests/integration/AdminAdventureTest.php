<?php

use LootTracker\Adventure\Admin\AdminAdventureLoot;

class AdminAdventureTest extends TestCase
{
    protected $adventure;
    public function setUp()
    {
        parent::setUp();
        $user = Sentry::findUserByLogin('admin');
        Sentry::login($user);
        $this->adventure = App::make('LootTracker\Adventure\Admin\AdminAdventureInterface');
    }

    /** @test */
    public function can_add_new_adventure()
    {
        //Add some adventures.
        $newAdventure = $this->setup_data_for_the_black_knights_adventure();
        $this->adventure->validator->updateRules($newAdventure);
        $this->assertTrue($this->adventure->validator->with($newAdventure)->passes());

        $newAdventure = $this->adventure->create($newAdventure);
        $this->assertNotNull($newAdventure, 'Something went wrong with saving the adventure!');

        //Test that they got added correctly.
        $this->assertCount(2, $this->adventure->findAllAdventures(), "Adventure haven't been added!");
        $this->assertCount(32, $newAdventure->loot);

        //Check that we can load the create page.
        $this->action('GET', 'AdminAdventureController@create');

        $this->assertResponseOk();

        //Get the adventure and check the loot has been added.
        $adventure = $this->adventure->findAdventureById($newAdventure->id);
        $this->assertNotNull($adventure);
        $this->assertCount(32, $adventure->loot); //Somewhat redundant, but atleast we're now sure all loot items got saved.
    }

    protected function setup_data_for_the_black_knights_adventure()
    {
        $data = array(
            'name' => 'The Black Knights',
            'items' => array(
                array('slot' => '1', 'type' => 'Exotic Wood Log', 'amount' => '1400'),
                array('slot' => 1, 'type' => 'Exotic Wood Log', 'amount' => '1600'),
                array('slot' => 1, 'type' => 'Granite', 'amount' => '1100'),
                array('slot' => 1, 'type' => 'Granite', 'amount' => '1300'),
                array('slot' => 1, 'type' => 'Saltpeter', 'amount' => '300'),
                array('slot' => 1, 'type' => 'Saltpeter', 'amount' => '400'),
                array('slot' => 1, 'type' => 'Titanium Ore', 'amount' => '200'),
                array('slot' => 1, 'type' => 'Titanium Ore', 'amount' => '300'),
                array('slot' => 2, 'type' => 'Hardwood Plank', 'amount' => '2000'),
                array('slot' => 2, 'type' => 'Marble', 'amount' => '2000'),
                array('slot' => 3, 'type' => 'Cannon', 'amount' => '150'),
                array('slot' => 3, 'type' => 'Crossbow', 'amount' => '500'),
                array('slot' => 3, 'type' => 'Damascene Sword', 'amount' => '300'),
                array('slot' => 3, 'type' => 'Steel Sword', 'amount' => '800'),
                array('slot' => 4, 'type' => 'Cannon', 'amount' => '150'),
                array('slot' => 4, 'type' => 'Crossbow', 'amount' => '500'),
                array('slot' => 4, 'type' => 'Damascene Sword', 'amount' => '300'),
                array('slot' => 4, 'type' => 'Steel Sword', 'amount' => '800'),
                array('slot' => 5, 'type' => 'Brew', 'amount' => '400'),
                array('slot' => 5, 'type' => 'Bread', 'amount' => '500'),
                array('slot' => 5, 'type' => 'Sausage', 'amount' => '200'),
                array('slot' => 5, 'type' => 'Settler', 'amount' => '400'),
                array('slot' => 6, 'type' => 'Angel Monument', 'amount' => '1'),
                array('slot' => 6, 'type' => 'Dark Castle', 'amount' => '1'),
                array('slot' => 6, 'type' => 'Gold Coin', 'amount' => '300'),
                array('slot' => 6, 'type' => 'Gold Coin', 'amount' => '600'),
                array('slot' => 6, 'type' => 'Wheat Refill', 'amount' => '3000'),
                array('slot' => 8, 'type' => 'Exotic Wood Log', 'amount' => '3400'),
                array('slot' => 8, 'type' => 'Granite', 'amount' => '2200'),
                array('slot' => 8, 'type' => 'Saltpeter', 'amount' => '3400'),
                array('slot' => 8, 'type' => 'Titanium Ore', 'amount' => '2060'),
                array('slot' => 8, 'type' => 'Nothing', 'amount' => '1')
            )
        );
        return $data;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
