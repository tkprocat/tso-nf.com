<?php

use LootTracker\Adventure\AdventureInterface;
use LootTracker\Loot\LootInterface;
use Authority\Repo\User\UserInterface;

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
     * @var UserInterface
     */
    protected $user;

    /**
     * @param LootInterface $loot
     * @param AdventureInterface $adventure
     */
    function __construct(LootInterface $loot, AdventureInterface $adventure, UserInterface $user)
    {
        $this->loot = $loot;
        $this->adventure = $adventure;
        $this->user = $user;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index($adventure_name = '')
    {
        $loots = $this->loot->paginate(25, $adventure_name);
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
        $user = $this->user->byUsername($username);

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
        //If for some reason the person posting isn't logged in, bail!
        $this->user->redirectNonAuthedUser();

        //Check it's an admin or it's the same user.
        $user_adventure = $this->loot->findUserAdventureById($id)->first();
        if ((!$this->user->IsAdmin()) && (!$this->user->CheckCurrentUserIs($user_adventure->User->id)))
            return Redirect::to('/')->with('error', 'Sorry you do not have permission to do this!');

        $adventures = $this->adventure->findAllAdventures();
        $adventure_loot = $this->adventure->findAllLootForAdventure($id);
        $user_adventure_loot = $this->loot->findAllLootForUserAdventure($id);
        $adventure = $user_adventure->adventure;

        $loot_slots = array();
        for ($slot = 1; $slot <= 20; $slot++) {
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
        $this->user->redirectNonAuthedUser();

        //Check it's an admin or it's the same user.
        $user_adventure = $this->loot->findUserAdventureById($id)->first();
        if (!$this->user->isAdmin() && !$this->user->checkCurrentUserIs($user_adventure->User->id))
            return Redirect::to('/')->with('error', 'Sorry you do not have permission to do this!');

        $data = Input::all();
        $data['user_id'] = $this->user->getUserID();
        $data['user_adventure_id'] = $id;
        $this->loot->validator->updateRules($data['adventure_id']);

        if ($this->loot->validator->with($data)->passes()) {
            //Passed validation, store the blog post.
            $this->loot->update($data);
            return Redirect::to('loot')->with('success', 'Loot updated successfully');
        } else {
            //Failed validation
            $adventure = $this->adventure->findAdventureById($data['adventure_id']);
            return Redirect::to('loot/'.$id.'/edit')->with('adventure', $adventure)->withInput()->withErrors($this->loot->validator->errors());
        }
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
        $this->user->redirectNonAuthedUser();

        $data = Input::all();
        $data['user_id'] = $this->user->getUserID();
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
    public function destroy($id)
    {
        if (!$this->user->check()) {
            App::abort(403, 'You are not authorized.');
        }

        $data = Input::all();

        if (is_numeric($id)) {
            //TODO: Make the repository handle the deletion.
            $useradventure = $this->loot->findUserAdventureById($id)->first();
            //Check if the userid deleting matches the userid on the record.

            if (($this->user->checkCurrentUserIs($useradventure->user_id)) || ($this->user->isAdmin())) {
                foreach ($useradventure->loot as $loot) {
                    $loot->delete();
                }
                $useradventure->delete();
            } else {
                App::abort(403, 'You are not authorized.');
            }
        } else {
            return \Response::json(array('status' => 'error', 'message' => 'Missing ID!'));
        }

        return \Response::json(array('status' => 'ok'));
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