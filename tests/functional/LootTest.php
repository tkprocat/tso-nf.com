<?php namespace LootTracker\Test\Functional;

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Test\TestCase;

class LootTest extends TestCase
{
    use DatabaseMigrations;

    /** @var \LootTracker\Repositories\Adventure\AdventureInterface $adventure */
    protected $adventure;

    /** @var \LootTracker\Repositories\Loot\LootInterface $loot */
    protected $loot;

    public function setUp()
    {
        parent::setUp();
        $this->login();
        $this->adventure = app(AdventureInterface::class);
        $this->loot = app(LootInterface::class);
    }

    /** @test */
    public function checkLootIndex()
    {
        $this->visit('loot')
            ->seePageIs('/loot');
    }

    /** @test */
    public function canGetLootIndexWithAdventureName()
    {
        $adventure = $this->setupDataForTheBlackKnightsAdventure();
        $this->visit('/loot/adventure/'.urlencode($adventure['name']));
    }

    /** @test */
    public function checkLatestLootForPlayer()
    {
        $username = $this->userRepo->getUser()->username;

        $this->visit('/loot/user/'.$username);
    }

    /** @test */
    public function canGetAdventureLootsFromJsonAPI()
    {
        //Add an adventure so we have something to reply.
        $adventure = $this->setupDataForTheBlackKnightsAdventure();

        //Test with GET
        $this->visit('/loot/getJSONLoot?adventure='.$adventure->id)
            ->seeJsonEquals($adventure->loot->toArray());

         //Test with POST
        $response = $this->call('POST', '/loot/getJSONLoot', array('adventure' => $adventure->id));
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
        $this->assertStringStartsWith(json_encode($adventure->loot->toArray()), $response->getContent());
    }

    /** @test */
    public function canLoadAddLootPage()
    {
        $this->visit('/loot/create')
            ->See('Add loot');
    }

    /** @test */
    public function canAddLoot()
    {
        //Simple test, we have too much javascript crap on this page to do proper testing here.
        $this->visit('/loot/create')
            ->see('Add loot')
            ->select('1', 'adventure_id');

        $adventure_id = 1;
        $data = array(
            'adventure_id' => $adventure_id,
            'slot1' => $this->loot->getLootByNameAndAmount($adventure_id, 1, 'Exotic Wood Log', 800)->id,
            'slot2' => $this->loot->getLootByNameAndAmount($adventure_id, 2, 'Exotic Wood Log', 400)->id,
            'slot3' => $this->loot->getLootByNameAndAmount($adventure_id, 3, 'Copper Ore', 1000)->id,
            'slot4' => $this->loot->getLootByNameAndAmount($adventure_id, 4, 'Iron Ore', 750)->id,
            'slot5' => $this->loot->getLootByNameAndAmount($adventure_id, 5, 'Coal', 1000)->id,
            'slot6' => $this->loot->getLootByNameAndAmount($adventure_id, 6, 'Pinewood Log', 1000)->id,
            'slot7' => $this->loot->getLootByNameAndAmount($adventure_id, 7, 'Hardwood Log', 750)->id,
            'slot8' => $this->loot->getLootByNameAndAmount($adventure_id, 8, 'Horse', 750)->id,
            'slot9' => $this->loot->getLootByNameAndAmount($adventure_id, 9, 'Settler', 350)->id,
            'slot10' => $this->loot->getLootByNameAndAmount($adventure_id, 10, 'Brew', 750)->id,
            'slot11' => $this->loot->getLootByNameAndAmount($adventure_id, 11, 'Gold Coin', 400)->id,
            'slot12' => $this->loot->getLootByNameAndAmount($adventure_id, 12, 'Meat', 400)->id,
            'slot13' => $this->loot->getLootByNameAndAmount($adventure_id, 13, 'Meat Deposit Refill', 400)->id,
            'slot14' => $this->loot->getLootByNameAndAmount($adventure_id, 14, 'Nothing', 1)->id,
            'slot15' => $this->loot->getLootByNameAndAmount($adventure_id, 15, 'Return to the Bandit Nest', 1)->id
        );

        $this->call('POST', '/loot', $data);
        $this->assertRedirectedTo('/loot/create', [
            'success' => 'Loot added successfully, <a href="/loot">click here to see your latest loot.</a>'
        ]);

        $user_adventure = $this->loot->all()->last();
        $this->assertNotNull($user_adventure);

        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 1, 'Exotic Wood Log', 800)->id,
            $user_adventure->loot()->slot(1)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 2, 'Exotic Wood Log', 400)->id,
            $user_adventure->loot()->slot(2)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 3, 'Copper Ore', 1000)->id,
            $user_adventure->loot()->slot(3)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 4, 'Iron Ore', 750)->id,
            $user_adventure->loot()->slot(4)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 5, 'Coal', 1000)->id,
            $user_adventure->loot()->slot(5)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 6, 'Pinewood Log', 1000)->id,
            $user_adventure->loot()->slot(6)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 7, 'Hardwood Log', 750)->id,
            $user_adventure->loot()->slot(7)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 8, 'Horse', 750)->id,
            $user_adventure->loot()->slot(8)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 9, 'Settler', 350)->id,
            $user_adventure->loot()->slot(9)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 10, 'Brew', 750)->id,
            $user_adventure->loot()->slot(10)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 11, 'Gold Coin', 400)->id,
            $user_adventure->loot()->slot(11)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 12, 'Meat', 400)->id,
            $user_adventure->loot()->slot(12)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 13, 'Meat Deposit Refill', 400)->id,
            $user_adventure->loot()->slot(13)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 14, 'Nothing', 1)->id,
            $user_adventure->loot()->slot(14)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 15, 'Return to the Bandit Nest', 1)->id,
            $user_adventure->loot()->slot(15)->first()->adventure_loot_id
        );
    }

    /** @test */
    public function canLoadEditLootPage()
    {
        $this->visit('/loot/1/edit')
            ->See('Update loot');
    }

     /** @test */
    public function canUpdateLoot()
    {
        /** @var \LootTracker\Repositories\Loot\LootInterface $this->loot */
        $this->loot = app(LootInterface::class);
        $adventure_id = 1;
        $data = array(
            'adventure_id' => $adventure_id,
            'slot1' => $this->loot->getLootByNameAndAmount($adventure_id, 1, 'Granite', 800)->id,
            'slot2' => $this->loot->getLootByNameAndAmount($adventure_id, 2, 'Titanium Ore', 400)->id,
            'slot3' => $this->loot->getLootByNameAndAmount($adventure_id, 3, 'Copper Ore', 1000)->id,
            'slot4' => $this->loot->getLootByNameAndAmount($adventure_id, 4, 'Iron Ore', 750)->id,
            'slot5' => $this->loot->getLootByNameAndAmount($adventure_id, 5, 'Coal', 1000)->id,
            'slot6' => $this->loot->getLootByNameAndAmount($adventure_id, 6, 'Pinewood Log', 1000)->id,
            'slot7' => $this->loot->getLootByNameAndAmount($adventure_id, 7, 'Hardwood Log', 750)->id,
            'slot8' => $this->loot->getLootByNameAndAmount($adventure_id, 8, 'Horse', 750)->id,
            'slot9' => $this->loot->getLootByNameAndAmount($adventure_id, 9, 'Settler', 350)->id,
            'slot10' => $this->loot->getLootByNameAndAmount($adventure_id, 10, 'Brew', 750)->id,
            'slot11' => $this->loot->getLootByNameAndAmount($adventure_id, 11, 'Gold Coin', 400)->id,
            'slot12' => $this->loot->getLootByNameAndAmount($adventure_id, 12, 'Meat', 400)->id,
            'slot13' => $this->loot->getLootByNameAndAmount($adventure_id, 13, 'Meat Deposit Refill', 400)->id,
            'slot14' => $this->loot->getLootByNameAndAmount($adventure_id, 14, 'Nothing', 1)->id,
            'slot15' => $this->loot->getLootByNameAndAmount($adventure_id, 15, 'Nothing', 1)->id
        );
        $this->call('PUT', '/loot/1', $data);
        $this->assertRedirectedTo('/loot/', array('success' => 'Loot updated successfully.'));

        $user_adventure = $this->loot->byId(1)->first();
        $this->assertNotNull($user_adventure);
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 1, 'Granite', 800)->id,
            $user_adventure->loot()->slot(1)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 2, 'Titanium Ore', 400)->id,
            $user_adventure->loot()->slot(2)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 3, 'Copper Ore', 1000)->id,
            $user_adventure->loot()->slot(3)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 4, 'Iron Ore', 750)->id,
            $user_adventure->loot()->slot(4)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 5, 'Coal', 1000)->id,
            $user_adventure->loot()->slot(5)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 6, 'Pinewood Log', 1000)->id,
            $user_adventure->loot()->slot(6)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 7, 'Hardwood Log', 750)->id,
            $user_adventure->loot()->slot(7)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 8, 'Horse', 750)->id,
            $user_adventure->loot()->slot(8)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 9, 'Settler', 350)->id,
            $user_adventure->loot()->slot(9)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 10, 'Brew', 750)->id,
            $user_adventure->loot()->slot(10)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 11, 'Gold Coin', 400)->id,
            $user_adventure->loot()->slot(11)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 12, 'Meat', 400)->id,
            $user_adventure->loot()->slot(12)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 13, 'Meat Deposit Refill', 400)->id,
            $user_adventure->loot()->slot(13)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 14, 'Nothing', 1)->id,
            $user_adventure->loot()->slot(14)->first()->adventure_loot_id
        );
        $this->assertEquals(
            $this->loot->getLootByNameAndAmount($adventure_id, 15, 'Nothing', 1)->id,
            $user_adventure->loot()->slot(15)->first()->adventure_loot_id
        );
    }

    /** @test */
    public function failsIfEditingAnotherUsersLoot()
    {
        $user = $this->userRepo->byUsername('user2');
        $this->userRepo->login($user);

        //Check that we get an error.
        $this->visit('/loot/1/edit')->see('Sorry you do not have permission to do this!');

        $data = array(
            'adventure_id' => '1',
            'slot1' => '2',
            'slot2' => '8',
            'slot3' => '10',
            'slot4' => '14',
            'slot5' => '18',
            'slot6' => '22',
            'slot8' => '28'
        );

        //Check that we get an error.
        $this->call('PUT', '/loot/1', $data);
        //$this->assertRedirectedTo('/loot', new MessageBag(['Sorry you do not have permission to do this!']));
        $this->assertRedirectedTo('/loot');
        $this->assertSessionHas('errors');
    }

    /** @test */
    public function canDeleteLoot()
    {
        $this->visit('/loot/')
            ->see('Delete');

        //Get loot count
        $count = $this->loot->all()->count();

        //Check we can load the edit page.
        $this->call('DELETE', '/loot/1');

        //Get new count
        $countNew = $this->loot->all()->count();
        $this->assertEquals($count - 1, $countNew);
    }

    /** @test */
    public function canGetLatestLootForASingleAdventure()
    {
        $user = $this->userRepo->getUser();
        $this->visit('/loot/user/'.$user->username.'/Bandit+Nest');
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
