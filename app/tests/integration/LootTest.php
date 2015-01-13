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
        $user = Sentry::findUserByLogin('admin');
        Sentry::login($user);
        $this->adventure = App::make('LootTracker\Adventure\AdventureInterface');
        $this->loot = App::make('LootTracker\Loot\LootInterface');
    }

    /** @test */
    public function check_loot_index()
    {
        $this->action('GET', 'LootController@index');
        $this->assertResponseOk();

        $this->call('GET', '/loot');
        $this->assertResponseOk();
    }

    /** @test */
    public function check_latest_loot_for_player()
    {
        $username = Sentry::getUser()->username;
        $this->action('GET', 'LootController@show', $username);
        $this->assertResponseOk();

        $this->call('GET', '/loot/'.$username);
        $this->assertResponseOk();
    }


    /** @test */
    public function can_add_loot_to_adventure()
    {
        //Test that we can add loot to it.
        $userAdventure = new UserAdventure;
        $userAdventure->adventure_id = 1;
        $userAdventure->user_id = Sentry::getUser()->getId();
        $this->assertTrue($userAdventure->validate(), $userAdventure->errors);
    }

    /** @test */
    public function can_get_loot_index_with_username()
    {
        $this->action('GET', 'LootController@index', '', array('username' => 'user'));

        $this->assertResponseOk();
    }

    /** @test */
    public function can_get_loot_index_without_username()
    {
        $this->action('GET', 'LootController@index');

        $this->assertResponseOk();
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
        $this->assertRedirectedTo('/loot/1/edit', array('success' => 'Loot updated successfully'));

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
    public function can_get_latest_loot_for_a_single_adventure() {
        $this->call('GET', '/loot/admin/Bandit+Nest');
        $this->assertResponseOk();
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}

