<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use LootTracker\Repositories\User\User;

class GuildSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        $user = User::whereUsername('user1')->firstOrFail();
        $guildRepo = App::make('LootTracker\Repositories\Guild\GuildInterface');
        $data = array(
            'name' => 'Tester\'s Guild',
            'tag' => 'TG'
        );
        $guildRepo->create($data, $user->id);
	}

}