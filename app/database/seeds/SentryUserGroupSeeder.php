<?php

class SentryUserGroupSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users_groups')->delete();

		$userUser1 = Sentry::getUserProvider()->findByLogin('user1');
        $userUser2 = Sentry::getUserProvider()->findByLogin('user2');
		$adminUser = Sentry::getUserProvider()->findByLogin('admin');

		$userGroup = Sentry::getGroupProvider()->findByName('Users');
		$adminGroup = Sentry::getGroupProvider()->findByName('Admins');

	    // Assign the groups to the users
	    $userUser1->addGroup($userGroup);
        $userUser2->addGroup($userGroup);
	    $adminUser->addGroup($userGroup);
	    $adminUser->addGroup($adminGroup);
	}

}