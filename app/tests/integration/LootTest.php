<?php

use LootTracker\Adventure\AdventureLoot;
use LootTracker\Loot\UserAdventure;

class LootTest extends TestCase
{

    protected $adventure;
    protected $loot;

    public function setUp()
    {
        parent::setUp();
        $this->login();
        Route::enableFilters();
        $this->adventure = App::make('LootTracker\Adventure\AdventureInterface');
        $this->loot = App::make('LootTracker\Loot\LootInterface');
    }

    /** @test */
    public function check_loot_index()
    {
        $this->call('GET', '/loot');
        $this->assertResponseOk();
    }

    /** @test */
    public function can_get_loot_index_with_adventure_name()
    {
        $this->call('GET', '/loot/adventure/Epic+-+The+Black+Knights');
        $this->assertResponseOk();
    }

    /** @test */
    public function check_latest_loot_for_player()
    {
        $username = $this->user->getUser()->username;

        $this->call('GET', '/loot/'.$username);
        $this->assertResponseOk();
    }

    /** @test */
    public function can_get_adventure_loots_from_json_api()
    {
        //Add an adventure so we have something to reply.
        $adventure = $this->add_the_black_knights_adventure();
        $json = json_encode($adventure->loot->toArray());

        //Test with GET
        $response = $this->call('GET', '/loot/getJSONLoot?adventure='.$adventure->id);
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
        $this->assertStringStartsWith($json, $response->getContent());

        //Test with POST
        $response = $this->call('POST', '/loot/getJSONLoot', array('adventure' => $adventure->id));
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
        $this->assertStringStartsWith($json, $response->getContent());
    }

    /** @test */
    public function can_load_add_loot_page()
    {
        $this->call('GET', '/loot/create');
        $this->assertResponseOk();
    }

    /** @test */
    public function can_add_loot()
    {
        $data = array(
            'adventure_id' => '1',
            'slot1' => '1',
            'slot2' => '7',
            'slot3' => '9',
            'slot4' => '13',
            'slot5' => '17',
            'slot6' => '21',
            'slot8' => '27'
        );
        $this->call('POST', '/loot', $data);
        $this->assertRedirectedTo('/loot/create', array('success' => 'Loot added successfully, <a href="/loot">click here to see your latest loot.</a>'));

        $user_adventure = $this->loot->findUserAdventureById(1)->first();
        $this->assertNotNull($user_adventure);
        $this->assertEquals(1, $user_adventure->loot()->slot(1)->first()->adventure_loot_id);
        $this->assertEquals(7, $user_adventure->loot()->slot(2)->first()->adventure_loot_id);
        $this->assertEquals(9, $user_adventure->loot()->slot(3)->first()->adventure_loot_id);
        $this->assertEquals(13, $user_adventure->loot()->slot(4)->first()->adventure_loot_id);
        $this->assertEquals(17, $user_adventure->loot()->slot(5)->first()->adventure_loot_id);
        $this->assertEquals(21, $user_adventure->loot()->slot(6)->first()->adventure_loot_id);
        $this->assertEquals(27, $user_adventure->loot()->slot(8)->first()->adventure_loot_id);
    }

    /** @test */
    public function will_get_error_adding_bogus_loot_in_slots()
    {
        $data = array(
            'adventure_id' => '1',
            'user_id' => '1',
            'slot1' => '99999',
            'slot2' => '99999',
            'slot3' => '99999',
            'slot4' => '99999',
            'slot5' => '99999',
            'slot6' => '99999',
            'slot7' => '99999',
            'slot8' => '99999'
        );

        $this->loot->validator->updateRules($data['adventure_id']);
        $this->loot->validator->with($data)->passes();
        $errors = $this->loot->validator->errors()->all();
        $this->assertEquals('The selected slot1 is invalid.', $errors[0]);
        $this->assertEquals('The selected slot2 is invalid.', $errors[1]);
        $this->assertEquals('The selected slot3 is invalid.', $errors[2]);
        $this->assertEquals('The selected slot4 is invalid.', $errors[3]);
        $this->assertEquals('The selected slot5 is invalid.', $errors[4]);
        $this->assertEquals('The selected slot6 is invalid.', $errors[5]);
        $this->assertEquals('The selected slot8 is invalid.', $errors[6]);
    }

    /** @test */
    public function can_update_loot()
    {
        //Check we can load the edit page.
        $this->call('GET', '/loot/1/edit');

        $data = array(
            'adventure_id' => '1',
            'slot1' => '2',
            'slot2' => '8',
            'slot3' => '10',
            'slot4' => '14',
            'slot5' => '18',
            'slot6' => '22',
            'slot8' => '28'
        );
        $this->call('PUT', '/loot/1', $data);
        $this->assertRedirectedTo('/loot/', array('success' => 'Loot updated successfully'));

        $user_adventure = $this->loot->findUserAdventureById(1)->first();
        $this->assertNotNull($user_adventure);
        $this->assertEquals(2, $user_adventure->loot()->slot(1)->first()->adventure_loot_id);
        $this->assertEquals(8, $user_adventure->loot()->slot(2)->first()->adventure_loot_id);
        $this->assertEquals(10, $user_adventure->loot()->slot(3)->first()->adventure_loot_id);
        $this->assertEquals(14, $user_adventure->loot()->slot(4)->first()->adventure_loot_id);
        $this->assertEquals(18, $user_adventure->loot()->slot(5)->first()->adventure_loot_id);
        $this->assertEquals(22, $user_adventure->loot()->slot(6)->first()->adventure_loot_id);
        $this->assertEquals(28, $user_adventure->loot()->slot(8)->first()->adventure_loot_id);
    }

    /** @test */
    public function fails_if_editing_another_users_loot()
    {
        $user = $this->user->byUsername('user2');
        $this->user->login($user);

        //Check that we get an error.
        $this->call('GET', '/loot/1/edit');
        $this->assertRedirectedTo('/', array('error' => 'Sorry you do not have permission to do this!'));

        $data = array(
            'adventure_id' => '1',
            'slot1' => '2',
            'slot2' => '8',
            'slot3' => '10',
            'slot4' => '14',
            'slot5' => '18',
            'slot6' => '22',
            'slot8' => '28'
        );

        //Check that we get an error.
        $this->call('PUT', '/loot/1', $data);
        $this->assertRedirectedTo('/', array('error' => 'Sorry you do not have permission to do this!'));
    }

    /** @test */
    public function can_delete_loot()
    {
        //Get loot count
        $count = $this->loot->all()->count();

        //Check we can load the edit page.
        $this->call('DELETE', '/loot/1');

        //Get new count
        $countNew = $this->loot->all()->count();
        $this->assertEquals($count - 1, $countNew);
    }

    /** @test */
    public function can_get_latest_loot_for_a_single_adventure() {
        $this->call('GET', '/loot/admin/Bandit+Nest');
        $this->assertResponseOk();
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

