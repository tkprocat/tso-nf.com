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

		$userUser = Sentry::getUserProvider()->findByLogin('user');
		$adminUser = Sentry::getUserProvider()->findByLogin('admin');

		$userGroup = Sentry::getGroupProvider()->findByName('Users');
		$adminGroup = Sentry::getGroupProvider()->findByName('Admins');

	    // Assign the groups to the users
	    $userUser->addGroup($userGroup);
	    $adminUser->addGroup($userGroup);
	    $adminUser->addGroup($adminGroup);
	}

}