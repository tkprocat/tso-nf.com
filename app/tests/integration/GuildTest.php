<?php

use Mockery as m;

class GuildTest extends TestCase
{
    protected $guild;

    public function setUp()
    {
        parent::setUp();
        $this->login();
        Route::enableFilters();
        $this->guild = App::make('LootTracker\Guild\GuildInterface');
    }

    /** @test */
    public function can_load_guild_list()
    {
        $this->call('GET', 'guilds');
        $this->assertResponseOk();
    }

    /** @test */
    public function can_load_specific_guild_page()
    {
        $guild = $this->create_LM_guild();
        $this->call('GET', 'guilds/'.$guild->id);
    }

    /** @test */
    public function can_create_new_guild()
    {
        $data = array(
            'id' => 2,
            'name' => 'Hidden Sanctuary',
            'tag' => 'HS'
        );
        $this->call('POST', 'guilds', $data);
        $this->assertRedirectedTo('/guilds', array('success' => 'Guild created successfully'));

        $guild = $this->guild->findTag('HS');
        $this->assertNotNull($guild);
        $this->assertEquals($data['name'], $guild->name);
        $this->assertEquals($data['tag'], $guild->tag);
        $this->assertEquals($this->user->getUser()->username, $guild->admins()[0]->username);
    }

    /** @test */
    public function creating_new_guild_creates_groups()
    {
        $guild1 = $this->create_LM_guild();
        $guild = $this->guild->findId($guild1->id);

        //Check the guild got created.
        $this->assertNotNull($guild);
    }

    /** @test */
    public function can_join_guild()
    {
        $guild1 = $this->create_LM_guild();
        $this->assertNotNull($guild1);
        $this->guild->addMember(1, $guild1->id);

        $this->user = $this->user->byUsername('user1');
        $this->assertEquals(1, $this->user->guild_id);
    }

    /** @test */
    public function can_not_join_another_guild_while_in_one()
    {
        $user = $this->login('user2');

        //Make sure the user aren't in a guild.
        $this->assertEquals(0, $user->guild_id);

        //Create new guild with the user as leader.
        $guild = $this->create_LM_guild();

        //Required since addMember changed the user information.
        $user = $this->login('user2');
        //Checks the user was correctly added
        $this->assertEquals($guild->id, $user->guild_id);

        //Use another user to make this guild
        $user = $this->login('user1');
        $data = array(
            'id' => 2,
            'name' => 'Guild 2',
            'tag' => 'G2'
        );
        $this->guild->create($data, $this->user->getUserID());

        //Make sure all is right.
        $user = $this->login('user2');
        $this->assertEquals($guild->id, $user->guild_id);

        //Attempt to add the user to second guild
        $this->guild->addMember(2, $user->id);

        //Required since addMember changed the user information.
        $user = $this->user->byUsername('user2');
        $this->assertEquals($guild->id, $user->guild_id);
    }

    /** @test */
    public function can_get_admins_for_guild()
    {
        $guild1 = $this->create_LM_guild();
        $this->assertCount(1, $guild1->admins());
        $this->assertEquals('user1', $guild1->admins()[0]->username);
    }

    /** @test */
    public function can_get_guild_by_tag()
    {
        $this->create_LM_guild();
        $guild = $this->guild->findTag('LM');
        $this->assertNotNull($guild);
        $this->assertEquals('LM', $guild->tag);
    }

    protected function create_LM_guild()
    {
        $data = array(
            'id' => 1,
            'name' => 'Lazy Monkeys',
            'tag' => 'LM'
        );
        return $this->guild->create($data, $this->user->getUser()->id);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }
}