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
    public function canCreateNewGuild()
    {
        $data = array(
            'name' => 'Hidden Sanctuary',
            'tag' => 'HS'
        );

        $this->visit('/guilds/create')
            ->type($data['name'], 'name')
            ->type($data['tag'], 'tag')
            ->press('Create')
            ->seePageIs('/guilds')
            ->see('Guild created successfully.');

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
            'name' => 'Drunk Monkeys',
            'tag' => 'DM'
        );

        $this->visit('/guilds/1/edit')
            ->type($data['name'], 'name')
            ->type($data['tag'], 'tag')
            ->press('Update')
            ->seePageIs('/guilds/1')
            ->see('Guild updated successfully.')
            ->seeInDatabase('guilds', ['tag' => $data['tag'], 'name' => $data['name']]);

        $guild = $this->guild->byTag('DM');
        $this->assertNotNull($guild);
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
        $this->setExpectedException('LootTracker\Repositories\Guild\UserAlreadyInAGuildException');
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

        //Test the button is there.
        $this->visit('/guilds/1')
            ->see('Lazy Monkeys')
            ->click('Disband guild');

        //Test that we can actually disband the guild.
        $this->call('DELETE', 'guilds/1');
        $this->assertRedirectedTo('/guilds', array('success' => 'Guild disbanded.'));
    }

    /** @test */
    public function canPromoteMember()
    {
        //Create test guild
        $this->createLMGuild();

        //Get the user id for user2
        $user2 = $this->user->byUsername('user2');

        //Just to set the expected return page.
        $this->visit('/guilds/1');

        //Check that we get a failure to promote the user since he is not currently a member.
        $this->visit('/guilds/1/promote/'.$user2->id)
            ->seePageIs('/guilds/1')
            ->see('That user is not a member of the guild.');

        //Add the user
        $this->visit('guilds/1/add/'.$user2->id)
            ->seePageIs('/guilds/1')
            ->see('User added to guild.');

        $user2 = $this->user->byUsername('user2');

        //Promote the user again.
        $this->visit('guilds/1/promote/'.$user2->id)
            ->seePageIs('/guilds/1')
            ->see('Member promoted.');

        $this->assertTrue($user2->hasRole('guild_admin'));
    }

    /** @test */
    public function canDemoteMember()
    {
        //Create test guild
        $this->createLMGuild();

        //Get the user id for user2
        $user2 = $this->user->byUsername('user2');

        //Just to set the expected return page.
        $this->visit('/guilds/1');

        //Add the user
        $this->visit('guilds/1/add/'.$user2->id)
            ->seePageIs('/guilds/1')
            ->see('User added to guild.');

        //Promote the user.
        $this->visit('guilds/1/promote/'.$user2->id)
            ->seePageIs('/guilds/1')
            ->see('Member promoted.');

        //Demote the user again.
        $this->visit('guilds/1/demote/'.$user2->id)
            ->seePageIs('/guilds/1')
            ->see('Member demoted.');

        $this->assertFalse($user2->hasRole('guild_admin'));
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