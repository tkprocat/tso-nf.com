<?php

use Mockery as m;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GuildTest extends TestCase
{
    protected $guild;

    public function setUp()
    {
        parent::setUp();
        $this->login();
        $this->guild = App::make('LootTracker\Repositories\Guild\GuildInterface');
    }

    /** @test */
    public function canLoadGuildList()
    {
        $this->call('GET', 'guilds');
        $this->assertResponseOk();
    }

    /** @test */
    public function canLoadSpecificGuildPage()
    {
        $guild = $this->createLMGuild();
        $this->call('GET', 'guilds/'.$guild->id);
    }

    /** @test */
    public function canLoadCreateNewGuildPage()
    {
        $this->call('GET', 'guilds/create');
        $this->assertResponseOk();
    }

    /** @test */
    public function canCreateNewGuild()
    {
        $data = array(
            'id' => 2,
            'name' => 'Hidden Sanctuary',
            'tag' => 'HS'
        );
        $this->call('POST', 'guilds', $data);
        $this->assertRedirectedTo('/guilds', array('success' => 'Guild created successfully.'));

        $guild = $this->guild->byTag('HS');
        $this->assertNotNull($guild);
        $this->assertEquals($data['name'], $guild->name);
        $this->assertEquals($data['tag'], $guild->tag);
        $this->assertEquals($this->user->getUser()->username, $guild->admins()[0]->username);
    }

    /** @test */
    public function canLoadUpdateGuildPage()
    {
        $this->createLMGuild();
        $this->call('GET', 'guilds/1/edit');
        $this->assertResponseOk();
    }

    /** @test */
    public function canUpdateGuild()
    {
        $this->createLMGuild();

        $data = array(
            'id' => 1,
            'name' => 'Drunk Monkeys',
            'tag' => 'DM'
        );
        $this->call('PUT', 'guilds/1', $data);
        $this->assertRedirectedTo('/guilds', array('success' => 'Guild updated successfully.'));

        $guild = $this->guild->byTag('DM');
        $this->assertNotNull($guild);
        $this->assertEquals($data['name'], $guild->name);
        $this->assertEquals($data['tag'], $guild->tag);
        $this->assertEquals($this->user->getUser()->username, $guild->admins()[0]->username);
    }

    /** @test */
    public function canJoinGuild()
    {
        $guild1 = $this->createLMGuild();
        $this->assertNotNull($guild1);
        $this->guild->addMember(1, $guild1->id);

        $this->user = $this->user->byUsername('user1');
        $this->assertEquals(1, $this->user->guild_id);
    }

    /** @test */
    public function canNotJoinAnotherGuildWhileInOne()
    {
        $user = $this->login('user2');

        //Make sure the user aren't in a guild.
        $this->assertEquals(0, $user->guild_id);

        //Create new guild with the user as leader.
        $guild = $this->createLMGuild();

        //Required since addMember changed the user information.
        $user = $this->login('user2');
        //Checks the user was correctly added
        $this->assertEquals($guild->id, $user->guild_id);

        //Use another user to make this guild
        $this->login('user1');
        $data = array(
            'id' => 2,
            'name' => 'Guild 2',
            'tag' => 'G2'
        );
        $this->guild->create($data, $this->user->getUser()->id);

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
    public function canGetAdminsForGuild()
    {
        $guild1 = $this->createLMGuild();
        $this->assertCount(1, $guild1->admins());
        $this->assertEquals('user1', $guild1->admins()[0]->username);
    }

    /** @test */
    public function canGetGuildByTag()
    {
        $this->createLMGuild();
        $guild = $this->guild->byTag('LM');
        $this->assertNotNull($guild);
        $this->assertEquals('LM', $guild->tag);
    }

    /** @test */
    public function canDeleteGuild()
    {
        $this->createLMGuild();
        $this->call('DELETE', 'guilds/1');
        $this->assertRedirectedTo('/guilds', array('success' => 'Guild deleted successfully.'));
    }

    /** @test */
    public function canPromoteMember()
    {
        //Create test guild
        $this->createLMGuild();

        //Get the user id for user2
        $user2 = $this->user->byUsername('user2');

        //Check that we get a failure to promote the user since he is not currently a member.
        $this->call('GET', 'guilds/1/promote/'.$user2->id, [], [], [], ['HTTP_REFERER' => 'guilds/1']);
        $this->assertRedirectedTo('guilds/1', array('errors' => 'That user is not a member of the guild.'));

        //Add the user
        $this->call('GET', 'guilds/1/add/'.$user2->id, [], [], [], ['HTTP_REFERER' => 'guilds/1']);
        $this->assertRedirectedTo('guilds/1', array('errors' => 'That user is not a member of the guild.'));
        $user2 = $this->user->byUsername('user2');

        //Promote the user again.
        $this->call('GET', 'guilds/1/promote/'.$user2->id, [], [], [], ['HTTP_REFERER' => 'guilds/1']);
        $this->assertRedirectedTo('guilds/1', array('success' => 'Member promoted.'));
    }

    protected function createLMGuild()
    {
        $data = array(
            'id' => 1,
            'name' => 'Lazy Monkeys',
            'tag' => 'LM'
        );

        $guild = $this->guild->create($data, $this->user->getUser()->id);

        //Relogin hack, since the previous method changes variables on the current user, we need to relogin.
        $this->login($this->user->getUser()->username);

        return $guild;
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }
}