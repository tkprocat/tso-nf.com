<?php

use LootTracker\Adventure\AdventureLoot;

class AdventureTest extends TestCase
{
    protected $adventure;
    public function setUp()
    {
        parent::setUp();
        $user = Sentry::findUserByLogin('admin');
        Sentry::login($user);
        $this->adventure = App::make('LootTracker\Adventure\AdventureInterface');
    }

    /** @test */
    public function make_sure_adventures_only_contains_bandit_nest()
    {
        $adventure_repository = App::make('LootTracker\Adventure\AdventureInterface');
        $this->assertCount(1, $adventure_repository->findAllAdventures(), 'Should (only) contain 1 adventure!');
    }

    /** @test */
    public function can_add_new_adventure()
    {
        //Add some adventures.
        $newAdventure = $this->add_the_black_knights_adventure();


        //Test that they got added correctly.
        $this->assertCount(2, $this->adventure->findAllAdventures(), "Adventure haven't been added!");
        $this->assertCount(32, $newAdventure->loot);

        //Check that we can load the create page.
        $this->action('GET', 'LootController@create');

        $this->assertResponseOk();

        //Get the adventure and check the loot has been added.
        $adventure = $this->adventure->findAdventureById($newAdventure->id);
        $this->assertNotNull($adventure);
        $this->assertCount(32, $adventure->loot); //Somewhat redundant, but atleast we're now sure all loot items got saved.
    }

    /** @test */
    public function can_get_adventure_loots_from_json_api()
    {
        //Expected result
        $json = '[{"id":"1","slot":"1","type":"Exotic Wood Log","amount":"1100","adventure_id":"1"';

        //Add an adventure so we have something to reply.
        $this->add_the_black_knights_adventure();

        //Test with GET
        $response = $this->call('GET', '/loot/getJSONLoot?adventure=1');
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
        $this->assertStringStartsWith($json, $response->getContent());

        //Test with POST
        $response = $this->call('POST', '/loot/getJSONLoot', array('adventure' => '1'));
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
        $this->assertStringStartsWith($json, $response->getContent());
    }

    protected function add_the_black_knights_adventure()
    {
        $data = array(
            'name' => 'The Black Knights',
            'slot1' => array(
                array('type' => 'Exotic Wood Log', 'amount' => '1400'),
                array('type' => 'Exotic Wood Log', 'amount' => '1600'),
                array('type' => 'Granite', 'amount' => '1100'),
                array('type' => 'Granite', 'amount' => '1300'),
                array('type' => 'Saltpeter', 'amount' => '300'),
                array('type' => 'Saltpeter', 'amount' => '400'),
                array('type' => 'Titanium Ore', 'amount' => '200'),
                array('type' => 'Titanium Ore', 'amount' => '300')
            ),
            'slot2' => array(
                array('type' => 'Hardwood Plank', 'amount' => '2000'),
                array('type' => 'Marble', 'amount' => '2000')
            ),
            'slot3' => array(
                array('type' => 'Cannon', 'amount' => '150'),
                array('type' => 'Crossbow', 'amount' => '500'),
                array('type' => 'Damascene Sword', 'amount' => '300'),
                array('type' => 'Steel Sword', 'amount' => '800')
            ),
            'slot4' => array(
                array('type' => 'Cannon', 'amount' => '150'),
                array('type' => 'Crossbow', 'amount' => '500'),
                array('type' => 'Damascene Sword', 'amount' => '300'),
                array('type' => 'Steel Sword', 'amount' => '800')
            ),
            'slot5' => array(
                array('type' => 'Brew', 'amount' => '400'),
                array('type' => 'Bread', 'amount' => '500'),
                array('type' => 'Sausage', 'amount' => '200'),
                array('type' => 'Settler', 'amount' => '400')
            ),
            'slot6' => array(
                array('type' => 'Angel Monument', 'amount' => '1'),
                array('type' => 'Dark Castle', 'amount' => '1'),
                array('type' => 'Gold Coin', 'amount' => '300'),
                array('type' => 'Gold Coin', 'amount' => '600'),
                array('type' => 'Wheat Refill', 'amount' => '3000'),
            ),
            'slot7' => array(

            ),
            'slot8' => array(
                array('type' => 'Exotic Wood Log', 'amount' => '3400'),
                array('type' => 'Granite', 'amount' => '2200'),
                array('type' => 'Saltpeter', 'amount' => '3400'),
                array('type' => 'Titanium Ore', 'amount' => '2060'),
                array('type' => 'Nothing', 'amount' => '1')
            ),
        );
        return $this->adventure->create($data);
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}

