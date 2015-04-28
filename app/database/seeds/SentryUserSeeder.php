<?php

class SentryUserSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->delete();

		Sentry::getUserProvider()->create(array(
	        'email'    => 'admin@admin.com',
            'username' => 'admin',
	        'password' => 'sentryadmin',
	        'activated' => 1,
	    ));

	    Sentry::getUserProvider()->create(array(
	        'email'    => 'user1@user.com',
            'username' => 'user1',
	        'password' => 'sentryuser',
	        'activated' => 1,
	    ));

        Sentry::getUserProvider()->create(array(
            'email'    => 'user2@user.com',
            'username' => 'user2',
            'password' => 'sentryuser',
            'activated' => 1,
        ));
	}

}