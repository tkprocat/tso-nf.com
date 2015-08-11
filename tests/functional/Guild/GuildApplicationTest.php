<?php namespace LootTracker\Test\Functional;

use App;
use LootTracker\Repositories\Guild\GuildApplication;
use LootTracker\Repositories\Guild\GuildInterface;
use LootTracker\Repositories\Guild\GuildApplicationInterface;
use LootTracker\Test\TestCase;

class GuildApplicationTest extends TestCase
{

    /**
     * @var $guildRepo \LootTracker\Repositories\Guild\GuildInterface
     */
    protected $guildRepo;

    /**
     * @var $guildApplicationRepo \LootTracker\Repositories\Guild\GuildApplicationInterface
     */
    protected $guildApplicationRepo;

    public function setUp()
    {
        parent::setUp();
        $this->login('user1');
        $this->guildRepo = App::make(GuildInterface::class);
        $this->guildApplicationRepo = App::make(GuildApplicationInterface::class);
    }

    /** @test */
    public function canApplyToGuild()
    {
        $user = $this->userRepo->getUser();
        $this->visit('guilds/1/applications/create')
            ->see("Application to join guild")
            ->type('Please accept my into your awesome guild!', 'message')
            ->press('Apply')
            ->seePageIs('/guilds')
            ->see('Your application have been registered.')
            ->seeInDatabase('guild_applications', ['user_id' => $user->id, 'guild_id' => '1',
                'message' => 'Please accept my into your awesome guild!']);
    }

    /** @test */
    public function canApproveApplication()
    {
        $application = $this->createGuildApplication();
        $this->visit('guilds/'.$application->guild_id.'/applications/'.$application->id)
            ->click('Approve')
            ->see('Member accepted to the guild.')
            ->seePageIs('guilds/1/edit');
    }

    /** @test */
    public function canDenyApplication()
    {
        $application = $this->createGuildApplication();
        $this->visit('guilds/'.$application->guild_id.'/applications/'.$application->id)
            ->click('Decline')
            ->see('Application declined.')
            ->seePageIs('guilds/1/edit');
    }

    /** @test */
    public function canSeeApplications()
    {
        $application = $this->createGuildApplication();
        $this->visit('guilds/'.$application->guild_id.'/edit/')
            ->see('Applications')
            ->see($application->user->username);
    }

    /** @test */
    public function canSeeApplicationDetails()
    {
        $application = $this->createGuildApplication();
        $this->visit('guilds/'.$application->guild_id.'/applications/'.$application->id)
            ->see('Applications')
            ->see($application->user->username);
    }


    private function createGuildApplication()
    {
        return GuildApplication::create([
            'user_id' => 3,
            'guild_id' => 1,
            'message' => 'Please accept my into your awesome guild!'
        ]);
    }
}
