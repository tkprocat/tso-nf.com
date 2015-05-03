<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

    protected $user;

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

    	return require __DIR__.'/../../bootstrap/start.php';
	}

    public function setUp()
    {
        parent::setUp();

        $this->user = \App::make('Authority\Repo\User\UserInterface');

        Artisan::call('migrate');
        Artisan::call('db:seed');
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
    }

    protected function login()
    {
        //Log in
        $user = $this->user->byUsername('admin');
        $this->user->login($user);
        return $user;
    }
}
