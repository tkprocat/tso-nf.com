<?php

use LootTracker\Adventure\AdventureInterface;
use LootTracker\Loot\LootInterface;

/**
 * Class LootController
 */
class LootController extends BaseController
{
    /**
     * @var string
     */
    protected $layout = 'layouts.default';

    /**
     * @var LootInterface
     */
    protected $loot;
    /**
     * @var AdventureInterface
     */
    protected $adventure;

    /**
     * @param LootInterface $loot
     * @param AdventureInterface $adventure
     */
    function __construct(LootInterface $loot, AdventureInterface $adventure)
    {
        $this->loot = $loot;
        $this->adventure = $adventure;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page = Input::get('page', 1);
        $lootsPerPage = 25;
        $pagiData = $this->loot->findPage($page, $lootsPerPage);

        $loots = Paginator::make(
            $pagiData->items,
            $pagiData->totalItems,
            $lootsPerPage
        );

        return View::make('loot.index')->with('loots', $loots);
    }

    /**
     * @param string $username
     * @param string $adventure_name
     * @return \Illuminate\View\View
     */
    public function show($username = '', $adventure_name = '')
    {

        //Make sure the username is filled out.
        if ($username == '')
            return Redirect::to('loot');

        //Get the user.
        $user = Sentry::findUserByLogin($username);

        $page = Input::get('page', 1);
        $lootPerPage = 25;
        //Show for selected user.
        $query = $this->loot->findAllAdventuresForUser($user->id)->orderBy('created_at', 'desc');
        if ($adventure_name != '')
            $query->where('adventure_id', $this->adventure->findAdventureByName(urldecode($adventure_name))->id);
        $loot = $query->skip($lootPerPage * ($page - 1))->take($lootPerPage)->get();

        //Set up the paginator!
        $loots = Paginator::make(
            $loot->all(),
            $loot->count(),
            $lootPerPage
        );

        return View::make('loot.index')->with('loots', $loots);
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $adventures = $this->adventure->findAllAdventures();
        $adventure_loot = $this->adventure->findAllLootForAdventure($id);
        $user_adventure_loot = $this->loot->findAllLootForUserAdventure($id);
        $user_adventure = $this->loot->findUserAdventureById($id);
        $adventure = $user_adventure->adventure;

        $loot_slots = array();
        for ($slot = 1; $slot <= 8; $slot++) {
            $loot_types = array();
            foreach ($adventure->loot()->slot($slot)->get() as $loot) {
                $loot_types[$loot->id] = $loot->type . ' - '.$loot->amount;
            }
            $loot_slots[$slot] = $loot_types;
        }

        return View::make('loot.edit')->with(array('useradventureloot' => $user_adventure_loot, 'useradventure' => $user_adventure, 'adventures' => $adventures, 'adventureloot' => $adventure_loot, 'loot_slots' => $loot_slots));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        //if for some reason the person posting isn't logged in, bail!
        if (!Sentry::check()) {
            return Redirect::to('/login');
        }

        $user_adventure = $this->loot->findUserAdventureById($id);

        if (!Sentry::hasAccess('admin') && $user_adventure->User->id != Sentry::getID())
            return Redirect::to('/')->with('error', 'Sorry you do not have permission to do this!');

        $data = Input::all();
        $data['user_id'] = Sentry::getID();
        $data['user_adventure_id'] = $id;
        $this->loot->validator->updateRules($data['adventure_id']);

        if ($this->loot->validator->with($data)->passes()) {
            //Passed validation, store the blog post.
            $this->loot->update($data);
            return Redirect::to('loot/'.$id.'/edit')->with('success', 'Loot updated successfully');
        } else {
            //Failed validation
            $adventure = $this->adventure->findAdventureById($data['adventure_id']);
            return Redirect::to('loot/'.$id.'/edit')->with('adventure', $adventure)->withInput()->withErrors($this->loot->validator->errors());
        }

       /*  $data = Input::all();

        $rules = array(
            'adventure' => 'required',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return Redirect::to('/register')->withErrors($validator->messages());
        }

        if ((isset($data['slot1'])) && (!isset($data['slot1_amount']))) {
            return Redirect::to('/register')->withErrors('Sorry, slot 1 amount needed to be filled out.');
        } else if ((isset($data['slot2'])) && (!isset($data['slot2_amount']))) {
            return Redirect::to('/register')->withErrors('Sorry, slot 2 amount needed to be filled out.');
        } else if ((isset($data['slot3'])) && (!isset($data['slot3_amount']))) {
            return Redirect::to('/register')->withErrors('Sorry, slot 3 amount needed to be filled out.');
        } else if ((isset($data['slot4'])) && (!isset($data['slot4_amount']))) {
            return Redirect::to('/register')->withErrors('Sorry, slot 4 amount needed to be filled out.');
        } else if ((isset($data['slot5'])) && (!isset($data['slot5_amount']))) {
            return Redirect::to('/register')->withErrors('Sorry, slot 5 amount needed to be filled out.');
        } else if ((isset($data['slot6'])) && (!isset($data['slot6_amount']))) {
            return Redirect::to('/register')->withErrors('Sorry, slot 6 amount needed to be filled out.');
        } else if ((isset($data['slot7'])) && (!isset($data['slot7_amount']))) {
            return Redirect::to('/register')->withErrors('Sorry, slot 7 amount needed to be filled out.');
        } else if ((isset($data['slot8'])) && (!isset($data['slot8_amount']))) {
            return Redirect::to('/register')->withErrors('Sorry, slot 8 amount needed to be filled out.');
        }

        $adventureID = $data['adventure'];
        $useradventure = UserAdventure::find($data['useradventureid']);
        $useradventure->save();

        //This is not pretty, but easy...
        UserAdventureLoot::where('UserAdventureID', '=', $data['useradventureid'])->delete();

        if ((isset($data['slot1'])) && ($data['slot1'] != '-1')) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->UserAdventureID = $useradventure->ID;
            $useradventureloot->AdventureLootID = AdventureLoot::where('Slot', '=', 1)->where('Amount', '=', $data['slot1_amount'])->where('Type', '=', $data['slot1'])->where('AdventureID', '=', $adventureID)->first()->ID;
            $useradventureloot->save();
        }
        if ((isset($data['slot2'])) && ($data['slot2'] != '-1')) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->UserAdventureID = $useradventure->ID;
            $useradventureloot->AdventureLootID = AdventureLoot::where('Slot', '=', 2)->where('Amount', '=', $data['slot2_amount'])->where('Type', '=', $data['slot2'])->where('AdventureID', '=', $adventureID)->first()->ID;
            $useradventureloot->save();
        }
        if ((isset($data['slot3'])) && ($data['slot3'] != '-1')) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->UserAdventureID = $useradventure->ID;
            $useradventureloot->AdventureLootID = AdventureLoot::where('Slot', '=', 3)->where('Amount', '=', $data['slot3_amount'])->where('Type', '=', $data['slot3'])->where('AdventureID', '=', $adventureID)->first()->ID;
            $useradventureloot->save();
        }
        if ((isset($data['slot4'])) && ($data['slot4'] != '-1')) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->UserAdventureID = $useradventure->ID;
            $useradventureloot->AdventureLootID = AdventureLoot::where('Slot', '=', 4)->where('Amount', '=', $data['slot4_amount'])->where('Type', '=', $data['slot4'])->where('AdventureID', '=', $adventureID)->first()->ID;
            $useradventureloot->save();
        }
        if ((isset($data['slot5'])) && ($data['slot5'] != '-1')) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->UserAdventureID = $useradventure->ID;
            $useradventureloot->AdventureLootID = AdventureLoot::where('Slot', '=', 5)->where('Amount', '=', $data['slot5_amount'])->where('Type', '=', $data['slot5'])->where('AdventureID', '=', $adventureID)->first()->ID;
            $useradventureloot->save();
        }
        if ((isset($data['slot6'])) && ($data['slot6'] != '-1')) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->UserAdventureID = $useradventure->ID;
            $useradventureloot->AdventureLootID = AdventureLoot::where('Slot', '=', 6)->where('Amount', '=', $data['slot6_amount'])->where('Type', '=', $data['slot6'])->where('AdventureID', '=', $adventureID)->first()->ID;
            $useradventureloot->save();
        }
        if ((isset($data['slot7'])) && ($data['slot7'] != '-1')) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->UserAdventureID = $useradventure->ID;
            $useradventureloot->AdventureLootID = AdventureLoot::where('Slot', '=', 7)->where('Amount', '=', $data['slot7_amount'])->where('Type', '=', $data['slot7'])->where('AdventureID', '=', $adventureID)->first()->ID;
            $useradventureloot->save();
        }
        if ((isset($data['slot8'])) && ($data['slot8'] != '-1')) {
            $useradventureloot = new UserAdventureLoot();
            $useradventureloot->UserAdventureID = $useradventure->ID;
            $useradventureloot->AdventureLootID = AdventureLoot::where('Slot', '=', 8)->where('Amount', '=', $data['slot8_amount'])->where('Type', '=', $data['slot8'])->where('AdventureID', '=', $adventureID)->first()->ID;
            $useradventureloot->save();
        }
        return Redirect::to('loot/latest'); */
    }

    /**
     * @param bool $popup_mode
     * @return \Illuminate\View\View
     */
    public function create($popup_mode = false)
    {
        $adventures = $this->adventure->getAdventuresWithLoot();
        //If we're recreating the view after a validation error, we need some extra info.
        if (Session::has('adventure'))
            $adventure = Session::get('adventure');
        else
            $adventure = $adventures->first();

        $loot_slots = array();
        for ($slot = 1; $slot <= 20; $slot++) {
            $loot_types = array();
            if ($adventure->loot()->slot($slot)->count() > 0)
                $loot_types[0] = "Please select loot.";
            foreach ($adventure->loot()->slot($slot)->get() as $loot) {
                $loot_types[$loot->id] = $loot->type . ' - '.$loot->amount;
            }
            $loot_slots[$slot] = $loot_types;
        }

        return View::make('loot.create', compact('adventures', 'adventure', 'loot_slots', 'popup_mode'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function createpopup()
    {
        return $this->create(true);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        //if for some reason the person posting isn't logged in, bail!
        if (!Sentry::check()) {
            return Redirect::to('/login');
        }

        $data = Input::all();
        $data['user_id'] = Sentry::getID();
        $this->loot->validator->updateRules($data['adventure_id']);

        if ($this->loot->validator->with($data)->passes()) {
            //Passed validation, store the blog post.
            $this->loot->create($data);
            return Redirect::to('loot/create')->with('success', 'Loot added successfully, <a href="/loot">click here to see your latest loot.</a>');
        } else {
            //Failed validation
            $adventure = $this->adventure->findAdventureById($data['adventure_id']);
            return Redirect::to('loot/create')->with('adventure', $adventure)->withInput()->withErrors($this->loot->validator->errors());
        }
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        if (!Sentry::check()) {
            return Redirect::route('login');
        }

        $data = Input::all();

        if (isset($data['id'])) {
            //TODO: Make the repository handle the deletion.
            $useradventure = $this->loot->findUserAdventureById($data['id']);
            //Check if the userid deleting matches the userid on the record.
            if ($useradventure->UserID == Sentry::getID()) {
                foreach ($useradventure->loot as $loot) {
                    $loot->delete();
                }
                $useradventure->delete();
            } else {
                App::abort(403, 'You are not authorized.');
            }
        }

        return \Rediect::to('loot');
    }

    /**
     * @return mixed
     */
    public function getLootForAdventure()
    {
        $data = Input::all();
        $result = $this->adventure->findAdventureById($data["adventure"])->loot()->orderBy('slot')->orderBy('type')->orderBy('amount')->get();
        return $result;
    }

}