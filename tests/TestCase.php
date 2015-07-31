<?php namespace LootTracker\Test;

use App;
use LootTracker\Console\Kernel;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Item\ItemInterface;
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


    /**
     * @return array
     */
    protected function setupDataForTheBlackKnightsAdventure()
    {
        $itemRepo = App::make(ItemInterface::class);
        $data = array(
            'name' => 'The Black Knights',
            'slot1' => array(
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '1400'),
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '1600'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '1100'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '1300'),
                array('item_id' => $itemRepo->byName('Saltpeter')->id, 'amount' => '300'),
                array('item_id' => $itemRepo->byName('Saltpeter')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '200'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '300'),
            ), 'slot2' => array(
                array('item_id' => $itemRepo->byName('Hardwood Plank')->id, 'amount' => '2000'),
                array('item_id' => $itemRepo->byName('Marble')->id, 'amount' => '2000'),
            ), 'slot3' => array(
                array('item_id' => $itemRepo->byName('Cannon')->id, 'amount' => '150'),
                array('item_id' => $itemRepo->byName('Crossbow')->id, 'amount' => '500'),
                array('item_id' => $itemRepo->byName('Damascene Sword')->id, 'amount' => '300'),
                array('item_id' => $itemRepo->byName('Steel Sword')->id, 'amount' => '800'),
            ), 'slot4' => array(
                array('item_id' => $itemRepo->byName('Cannon')->id, 'amount' => '150'),
                array('item_id' => $itemRepo->byName('Crossbow')->id, 'amount' => '500'),
                array('item_id' => $itemRepo->byName('Damascene Sword')->id, 'amount' => '300'),
                array('item_id' => $itemRepo->byName('Steel Sword')->id, 'amount' => '800'),
            ), 'slot5' => array(
                array('item_id' => $itemRepo->byName('Brew')->id, 'amount' => '400'),
                array('item_id' => $itemRepo->byName('Bread')->id, 'amount' => '500'),
                array('item_id' => $itemRepo->byName('Sausage')->id, 'amount' => '200'),
                array('item_id' => $itemRepo->byName('Settler')->id, 'amount' => '400'),
            ), 'slot6' => array(
                array('item_id' => $itemRepo->byName('Angel Monument')->id, 'amount' => '1'),
                array('item_id' => $itemRepo->byName('Dark Castle')->id, 'amount' => '1'),
                array('item_id' => $itemRepo->byName('Gold Coin')->id, 'amount' => '300'),
                array('item_id' => $itemRepo->byName('Gold Coin')->id, 'amount' => '600'),
                array('item_id' => $itemRepo->byName('Wheat Refill')->id, 'amount' => '3000'),
            ), 'slot8' => array(
                array('item_id' => $itemRepo->byName('Exotic Wood Log')->id, 'amount' => '3400'),
                array('item_id' => $itemRepo->byName('Granite')->id, 'amount' => '2200'),
                array('item_id' => $itemRepo->byName('Saltpeter')->id, 'amount' => '3400'),
                array('item_id' => $itemRepo->byName('Titanium Ore')->id, 'amount' => '2060'),
                array('item_id' => $itemRepo->byName('Nothing')->id, 'amount' => '1')
            )
        );
        $adventureRepo = App::make(AdventureInterface::class);
        return $adventureRepo->create($data);
    }
}
