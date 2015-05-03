<?php

class GuildSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        $user = Sentry::findUserByLogin('user1');
        $guildRepo = App::make('LootTracker\Guild\GuildInterface');
        $data = array(
            'name' => 'Tester\'s Guild',
            'tag' => 'TG'
        );
        $guildRepo->create($data, $user->id);
	}

}