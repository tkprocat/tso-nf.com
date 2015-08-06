<?php namespace LootTracker\Test;

use App;
use \LootTracker\Repositories\Guild\GuildInterface;
use LootTracker\Repositories\User\Role;

class GuildTest extends TestCase
{

    /**
     * @var $guild \LootTracker\Repositories\Guild\GuildInterface
     */
    protected $guild;

    /**
     * @var \LootTracker\Repositories\User\Role
     */
    protected $memberRole;

    /**
     * @var \LootTracker\Repositories\User\Role
     */
    protected $adminRole;

    public function setUp()
    {
        parent::setUp();
        $this->login('user1');
        $this->guild = App::make(GuildInterface::class);

        $this->memberRole = Role::whereName('guild_member')->first();
        $this->adminRole = Role::whereName('guild_admin')->first();
    }

    /** @test */
    public function canLoadGuildList()
    {
        $this->visit('guilds')
            ->see("Tester&#039;s Guild");
    }

    /** @test */
    public function canLoadSpecificGuildPage()
    {
        $this->visit('guilds/1')
            ->see("Tester&#039;s Guild");
    }

    /** @test */
    public function canCreateNewGuild()
    {
        $this->login('user2');
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
        $this->assertEquals($this->userRepo->getUser()->username, $guild->admins[0]->username);
    }

    /** @test */
    public function canLoadUpdateGuildPage()
    {
        $this->login('user1');
        $this->visit('guilds/1/edit')
            ->see('Edit guild');
    }

    /** @test */
    public function canUpdateGuild()
    {
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
        $this->assertEquals($this->userRepo->getUser()->username, $guild->admins[0]->username);
    }

    /** @test */
    public function canJoinGuild()
    {
        $user2 = $this->userRepo->byUsername('user2');
        $this->guild->addMember(1, $user2->id);
        $this->userRepo = $this->userRepo->byUsername('user2');
        $this->assertEquals(1, $this->userRepo->guild_id);
    }

    /** @test */
    public function canNotJoinAnotherGuildWhileInOne()
    {
        $user = $this->login('user2');

        //Make sure the user aren't in a guild.
        $this->assertEquals(0, $user->guild_id);

        //Required since addMember changed the user information.
        $user = $this->login('user1');
        //Checks the user was correctly added
        $this->assertEquals(1, $user->guild_id);

        //Use another user to make this guild
        $this->login('user2');
        $data = array(
            'id' => 2,
            'name' => 'Guild 2',
            'tag' => 'G2'
        );
        $this->guild->create($data, $this->userRepo->getUser()->id);

        //Make sure all is right.
        $user = $this->login('user2');
        $this->assertEquals(2, $user->guild_id);

        //Attempt to add the user to second guild
        $this->setExpectedException('LootTracker\Repositories\Guild\UserAlreadyInAGuildException');
        $this->guild->addMember(2, $user->id);

        //Required since addMember changed the user information.
        $user = $this->userRepo->byUsername('user2');
        $this->assertEquals(1, $user->guild_id);
    }

    /** @test */
    public function canKickMember()
    {
        $user2 = $this->userRepo->byUsername('user2');
        $this->guild->addMember(1, $user2->id);

        //Check the user is added to the guild
        $this->seeInDatabase('users', array('id' => $user2->id, 'guild_id' => '1'));

        //Kick the user again and check it gets cleaned up.
        $this->visit('guilds/1/kick/'.$user2->id)
            ->see('Guild member kicked.')
            ->seeInDatabase('users', array('id' => $user2->id, 'guild_id' => '0'))
            ->notSeeInDatabase('role_user', ['user_id' => $user2->id, 'role_id' => $this->memberRole->id])
            ->notSeeInDatabase('role_user', ['user_id' => $user2->id, 'role_id' => $this->adminRole->id]);
    }

    /** @test */
    public function canGetAdminsForGuild()
    {
        $guild1 = $this->guild->byTag('TG');
        $this->assertCount(1, $guild1->admins);
        $this->assertEquals('user1', $guild1->admins[0]->username);
    }

    /** @test */
    public function canGetGuildByTag()
    {
        $guild = $this->guild->byTag('TG');
        $this->assertNotNull($guild);
        $this->assertEquals('TG', $guild->tag);
    }

    /** @test */
    public function canDisbandGuild()
    {
        //Test the button is there.
        $this->visit('/guilds/1/edit')
            ->see('Tester&#039;s Guild')
            ->click('Disband guild');

        //Test that we can actually disband the guild.
        $this->delete('guilds/1');

        $this->notSeeInDatabase('users', array('guild_id' => '1'));
    }

    /** @test */
    public function canPromoteMember()
    {
        //Get the user id for user2
        $user2 = $this->userRepo->byUsername('user2');

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

        $user2 = $this->userRepo->byUsername('user2');

        //Promote the user again.
        $this->visit('guilds/1/promote/'.$user2->id)
            ->seePageIs('/guilds/1')
            ->see('Member promoted.');

        $this->assertTrue($user2->hasRole('guild_admin'));
    }

    /** @test */
    public function canDemoteMember()
    {
        //Get the user id for user2
        $user2 = $this->userRepo->byUsername('user2');

        //Just to set the expected return page.
        $this->visit('/guilds/1');

        //Add the user
        $this->visit('guilds/1/add/'.$user2->id)
            ->seePageIs('/guilds/1')
            ->see('User added to guild.')
            ->seeInDatabase('users', ['id' => $user2->id, 'guild_id' => '1'])
            ->seeInDatabase('role_user', ['user_id' => $user2->id, 'role_id' => $this->memberRole->id])
            ->notSeeInDatabase('role_user', ['user_id' => $user2->id, 'role_id' => $this->adminRole->id]);

        //Promote the user.
        $this->visit('guilds/1/promote/'.$user2->id)
            ->seePageIs('/guilds/1')
            ->see('Member promoted.')
            ->seeInDatabase('role_user', ['user_id' => $user2->id, 'role_id' => $this->memberRole->id])
            ->seeInDatabase('role_user', ['user_id' => $user2->id, 'role_id' => $this->adminRole->id]);

        //Demote the user again.
        $this->visit('guilds/1/demote/'.$user2->id)
            ->seePageIs('/guilds/1')
            ->see('Member demoted.')
            ->seeInDatabase('role_user', ['user_id' => $user2->id, 'role_id' => $this->memberRole->id])
            ->notSeeInDatabase('role_user', ['user_id' => $user2->id, 'role_id' => $this->adminRole->id]);
    }

    /** @test */
    public function failsDemotingLastAdmin()
    {
        //Get the user id for user2
        $user1 = $this->userRepo->byUsername('user1');

        $this->visit('guilds/1');

        //Demote the user again.
        $this->visit('guilds/1/demote/'.$user1->id)
            ->seePageIs('/guilds/1')
            ->see('You can not demote the last admin in the guild, either promote a new one or disband the guild.')
            ->seeInDatabase('role_user', ['user_id' => $user1->id, 'role_id' => $this->memberRole->id])
            ->seeInDatabase('role_user', ['user_id' => $user1->id, 'role_id' => $this->adminRole->id]);
    }

    protected function createLMGuild()
    {
        $data = array(
            'id' => 2,
            'name' => 'Lazy Monkeys',
            'tag' => 'LM'
        );

        $guild = $this->guild->create($data, $this->userRepo->getUser()->id);

        //Relogin hack, since the previous method changes variables on the current user, we need to relogin.
        $this->login($this->userRepo->getUser()->username);

        return $guild;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
