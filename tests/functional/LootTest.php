<?php namespace LootTracker\Test\Functional;

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Test\TestCase;

class LootTest extends TestCase
{
    use DatabaseMigrations;

    protected $adventure;
    protected $loot;

    public function setUp()
    {
        parent::setUp();
        $this->login();
        $this->adventure = App::make(AdventureInterface::class);
        $this->loot = App::make(LootInterface::class);
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

        $data = array(
            'adventure_id' => '1',
            'slot1' => '1',
            'slot2' => '7',
            'slot3' => '9',
            'slot4' => '13',
            'slot5' => '17',
            'slot6' => '21',
            'slot8' => '27'
        );
        $this->call('POST', '/loot', $data);
        $this->assertRedirectedTo('/loot/create', [
            'success' => 'Loot added successfully, <a href="/loot">click here to see your latest loot.</a>'
        ]);

        $user_adventure = $this->loot->byId(1)->first();
        $this->assertNotNull($user_adventure);
        $this->assertEquals(1, $user_adventure->loot()->slot(1)->first()->adventure_loot_id);
        $this->assertEquals(7, $user_adventure->loot()->slot(2)->first()->adventure_loot_id);
        $this->assertEquals(9, $user_adventure->loot()->slot(3)->first()->adventure_loot_id);
        $this->assertEquals(13, $user_adventure->loot()->slot(4)->first()->adventure_loot_id);
        $this->assertEquals(17, $user_adventure->loot()->slot(5)->first()->adventure_loot_id);
        $this->assertEquals(21, $user_adventure->loot()->slot(6)->first()->adventure_loot_id);
        $this->assertEquals(27, $user_adventure->loot()->slot(8)->first()->adventure_loot_id);
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
        $this->call('PUT', '/loot/1', $data);
        $this->assertRedirectedTo('/loot/', array('success' => 'Loot updated successfully.'));

        $user_adventure = $this->loot->byId(1)->first();
        $this->assertNotNull($user_adventure);
        $this->assertEquals(2, $user_adventure->loot()->slot(1)->first()->adventure_loot_id);
        $this->assertEquals(8, $user_adventure->loot()->slot(2)->first()->adventure_loot_id);
        $this->assertEquals(10, $user_adventure->loot()->slot(3)->first()->adventure_loot_id);
        $this->assertEquals(14, $user_adventure->loot()->slot(4)->first()->adventure_loot_id);
        $this->assertEquals(18, $user_adventure->loot()->slot(5)->first()->adventure_loot_id);
        $this->assertEquals(22, $user_adventure->loot()->slot(6)->first()->adventure_loot_id);
        $this->assertEquals(28, $user_adventure->loot()->slot(8)->first()->adventure_loot_id);
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
