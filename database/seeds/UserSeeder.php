<?php

use Illuminate\Database\Seeder;
use LootTracker\Repositories\User\Role;
use LootTracker\Repositories\User\User;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRole = Role::whereName('user')->first();
        $adminRole = Role::whereName('admin')->first();
        $admin = User::create(array(
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password' => Hash::make('sentryadmin'),
            'activated' => 1,
        ));
        $admin->attachRole($userRole);
        $admin->attachRole($adminRole);

        $user1 = User::create(array(
            'email' => 'user1@user.com',
            'username' => 'user1',
            'password' => Hash::make('sentryuser'),
            'activated' => 1,
        ));
        $user1->attachRole($userRole);

        $user2 = User::create(array(
            'email' => 'user2@user.com',
            'username' => 'user2',
            'password' => Hash::make('sentryuser'),
            'activated' => 1,
        ));
        $user2->attachRole($userRole);
    }
}