<?php

use Mockery as m;

class GuildTest extends TestCase
{
    protected $guild;
    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->login();
        Route::enableFilters();
        $this->guild = App::make('LootTracker\Guild\GuildInterface');
        $this->user = App::make('Authority\Repo\User\UserInterface');
    }

    public function test_index_page()
    {
        $this->call('GET', 'guilds');
        $this->assertResponseOk();
    }

    public function test_specific_guild_page()
    {
        $data = array(
            'id' => 1,
            'name' => 'Lazy Monkeys',
            'tag' => 'LM'
        );
        $this->guild->create($data, $this->user->getUser()->id);
        $this->call('GET', 'guilds/1');
    }

    public function testGroupsExistsAfterCreatingGuild()
    {
        $data = array(
            'id' => 1,
            'name' => 'Lazy Monkeys',
            'tag' => 'LM'
        );
        $this->guild->create($data, $this->user->getUser()->id);
        $guild = $this->guild->findId(1);

        //Check the guild got created.
        $this->assertNotNull($guild);
    }

    public function testMemberCanJoinGuild()
    {
        $data = array(
            'id' => 1,
            'name' => 'Lazy Monkeys',
            'tag' => 'LM'
        );
        $this->guild->create($data, $this->user->getUser()->id);

        $guild1 = $this->guild->findId($data['id']);
        $this->assertNotNull($guild1);
        $this->guild->addMember(1, $data['id']);

        $this->user = $this->user->byUsername('admin');
        $this->assertEquals(2, $this->user->guild_id);
    }

    public function testMemberFailsJumpingGuild()
    {
        $return = $this->login();

        $data = array(
            'id' => 1,
            'name' => 'Lazy Monkeys',
            'tag' => 'LM'
        );
        $this->guild->create($data, $this->user->getUser()->id);
        $this->guild->addMember(1, $user->id);

        //Required since addMember changed the user information.
        $user = $this->user->byUsername('admin');
        $this->assertEquals(2, $user->guild_id);

        $data = array(
            'id' => 2,
            'name' => 'Guild 2',
            'tag' => 'G2'
        );
        $this->guild->create($data, $this->user->getUser()->id);
        $this->guild->addMember(2, $user->id);

        //Required since addMember changed the user information.
        $user = $this->user->byUsername('admin');
        $this->assertEquals(2, $user->guild_id);
    }

    public function testCanGetAdmins()
    {
        $data = array(
            'id' => 1,
            'name' => 'Lazy Monkeys',
            'tag' => 'LM'
        );
        $this->guild->create($data, $this->user->getUser()->id);

        $guild1 = $this->guild->findId($data['id']);
        $this->assertEquals('admin', $guild1->admins()[0]->username);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }
}