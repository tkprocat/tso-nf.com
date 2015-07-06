<?php

use Illuminate\Support\Facades\App;
use LootTracker\Repositories\User\Role;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    protected $baseUrl = 'http://localhost';

    protected $user;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        $this->user = App::make(LootTracker\Repositories\User\UserInterface::class);
        $this->artisan('migrate');
        $this->artisan('db:seed');

//        $user1 = $this->user->byUsername('user1');
//        $user2 = $this->user->byUsername('user2');
//        $admin = $this->user->byUsername('admin');
//        $userRole = Role::whereName('user')->first();
//        $adminRole = Role::whereName('admin')->first();
//        $user1->attachRole($userRole);
//        $user2->attachRole($userRole);
//        $admin->attachRole($userRole);
//        $admin->attachRole($adminRole);
    }

    public function tearDown()
    {
        $this->artisan('migrate:rollback');
    }

    /**
     * @param string $username
     * @return mixed
     */
    protected function login($username = 'user1')
    {
        //Log in
        $user = $this->user->byUsername($username);
        $this->be($user);

        return $user;
    }

    /**
     * @return mixed
     */
    protected function loginAsAdmin()
    {
        //Log in
        $user = $this->user->byUsername('admin');
        $this->be($user);

        return $user;
    }

}
