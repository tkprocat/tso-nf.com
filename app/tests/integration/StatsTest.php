<?php

class StatsTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $user = Sentry::findUserByLogin('admin');
        Sentry::login($user);
    }

    /** @test */
    public function check_stat_global_index()
    {
        $this->call('GET', 'stats/global');
        $this->assertResponseOk();
    }

    /** @test */
    public function check_personal_stats_for_user()
    {
        $this->call('GET', 'stats/personal/admin');
        $this->assertResponseOk();
    }

    /** @test */
    public function check_accumulated_loot_for_player()
    {
        $this->call('GET', 'stats/accumulatedloot/admin/2013-01-01/2030-12-31');
        $this->assertResponseOk();
    }

    /** @test */
    public function check_played_adventure_for_user()
    {
        $this->call('GET', 'stats/adventuresplayed/admin/2013-01-01/2030-12-31');
        $this->assertResponseOk();
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}