<?php namespace LootTracker\Test;

use App;
use LootTracker\Console\Kernel;
use LootTracker\Repositories\User\UserInterface;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{

    /**
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * @var $userRepo \LootTracker\Repositories\User\UserInterface
     */
    protected $userRepo;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        $this->userRepo = App::make(UserInterface::class);
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    public function tearDown()
    {
        parent::tearDown();
        //$this->artisan('migrate:rollback');
    }

    /**
     * @param string $username
     * @return mixed
     */
    protected function login($username = 'user1')
    {
        //Log in
        $user = $this->userRepo->byUsername($username);
        $this->be($user);

        return $user;
    }

    /**
     * @return mixed
     */
    protected function loginAsAdmin()
    {
        //Log in
        $user = $this->userRepo->byUsername('admin');
        $this->be($user);

        return $user;
    }
}
