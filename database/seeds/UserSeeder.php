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
            'email' => 'admin@tso-nf.com',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'activated' => 1,
        ));
        $admin->attachRole($userRole);
        $admin->attachRole($adminRole);

        $user1 = User::create(array(
            'email' => 'user1@tso-nf.com',
            'username' => 'user1',
            'password' => Hash::make('user1'),
            'activated' => 1,
        ));
        $user1->attachRole($userRole);

        $user2 = User::create(array(
            'email' => 'user2@tso-nf.com',
            'username' => 'user2',
            'password' => Hash::make('user2'),
            'activated' => 1,
        ));
        $user2->attachRole($userRole);

        $user3 = User::create(array(
            'email' => 'user3@tso-nf.com',
            'username' => 'user3',
            'password' => Hash::make('user3'),
            'activated' => 1,
        ));
        $user3->attachRole($userRole);
    }
}