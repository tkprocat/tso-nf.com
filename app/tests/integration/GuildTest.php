<?php

use Mockery as m;
use Illuminate\Database\Eloquent\Collection;
use LootTracker\Guild\GuildInterface;
use LootTracker\Guild\Guild;
use LootTracker\Guild\DbGuildRepository;

class GuildTest extends TestCase
{
    protected $guild;

    public function setUp()
    {
        parent::setUp();
        Route::enableFilters();
        $this->guild = App::make('LootTracker\Guild\GuildInterface');
    }

    public function test_index_page()
    {
        $data = new \StdClass;
        $data->page = 1;
        $data->limit = 10;
        $data->items = array();
        $data->totalItems = 0;

        $this->call('GET', 'guilds');
        $this->assertResponseOk();
    }

    public function test_specific_guild_page()
    {
       // $user = Sentry::findUserByLogin('admin');
       // Sentry::login($user);

        $data = array(
            'id' => 1,
            'name' => 'Lazy Monkeys',
            'tag' => 'LM'
        );
        $this->guild->create($data);

        $this->call('GET', 'guilds/1');

        //$this->assertResponseOk();
       // Sentry::logout();
    }

    public function testGroupsExistsAfterCreatingGuild()
    {
        $data = array(
            'id' => 1,
            'name' => 'Lazy Monkeys',
            'tag' => 'LM'
        );
        $this->guild->create($data);
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
        $this->guild->create($data);

        $guild1 = $this->guild->findId($data['id']);
        $this->assertNotNull($guild1);
        $this->guild->addMember(1, $data['id']);

        $this->user = Sentry::findUserByLogin('admin');
        $this->assertEquals(1, $this->user->guild_id);
    }

    public function testMemberFailsJumpingGuild()
    {
        $user = Sentry::findUserByLogin('admin');
        Sentry::login($user);

        $data = array(
            'id' => 1,
            'name' => 'Lazy Monkeys',
            'tag' => 'LM'
        );
        $this->guild->create($data);
        $this->guild->addMember(1, $user->id);

        //Required since addMember changed the user information.
        $user = Sentry::findUserByLogin('admin');
        $this->assertEquals(1, $user->guild_id);

        $data = array(
            'id' => 2,
            'name' => 'Guild 2',
            'tag' => 'G2'
        );
        $this->guild->create($data);
        $this->guild->addMember(2, $user->id);

        //Required since addMember changed the user information.
        $user = Sentry::findUserByLogin('admin');
        $this->assertEquals(1, $user->guild_id);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }
}