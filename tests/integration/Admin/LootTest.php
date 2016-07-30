<?php

namespace LootTracker\Test\Integration;

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Test\TestCase;

class LootTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var $lootRepo LootInterface
     */
    protected $lootRepo;

    public function setUp()
    {
        parent::setUp();
        $this->login();
        $this->lootRepo = App::make(LootInterface::class);
    }

    /** @test */
    public function canGetAllPlayedAdventures()
    {
        $userAdventures = $this->lootRepo->all();
        $this->assertCount(3, $userAdventures);
    }
}
