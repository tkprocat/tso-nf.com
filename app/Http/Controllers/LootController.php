<?php namespace LootTracker\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Validator;
use App;
use LootTracker\Repositories\Adventure\AdventureInterface;
use LootTracker\Repositories\Loot\LootInterface;
use LootTracker\Repositories\User\UserInterface;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

/**
 * Class LootController
 */
class LootController extends Controller
{
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
     * @param UserInterface $user
     */
    function __construct(LootInterface $loot, AdventureInterface $adventure, UserInterface $user)
    {
        $this->loot = $loot;
        $this->adventure = $adventure;
        $this->user = $user;
    }

    /**
     * @param string $adventure_name
     * @return \Illuminate\View\View
     */
    public function index($adventure_name = '')
    {
        $loots = $this->loot->paginate(25, urldecode($adventure_name));
        return view('loot.index')->with('loots', $loots);
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
        $user_id = $this->user->byUsername($username)->id;

        $loots = $this->loot->paginate(25, $adventure_name, $user_id);
        return view('loot.index')->with('loots', $loots);
    }

    /**
     * @param $user_adventure_id
     * @return \Illuminate\View\View
     */
    public function edit($user_adventure_id)
    {
        //Check it's an admin or it's the same user.
        $user_adventure = $this->loot->byId($user_adventure_id);

        if (!($this->user->IsAdmin() || $this->user->getUser()->id === $user_adventure->User->id))
            return Redirect::to('/loot')->with('error', 'Sorry you do not have permission to do this!');

        $adventures = $this->adventure->all();
        //$adventure_loot = $this->adventure->findAllLootForAdventure($user_adventure->id);
        //$user_adventure_loot = $this->loot->findAllLootForUserAdventure($id);
        $adventure = $user_adventure->adventure;

        $loot_slots = array();
        for ($slot = 1; $slot <= 20; $slot++) {
            $loot_types = array();
            if ($adventure->loot()->slot($slot)->count() > 0)
                $loot_types[0] = "Please select loot.";
            foreach ($adventure->loot()->slot($slot) as $loot) {
                $loot_types[$loot->id] = $loot->type . ' - '.$loot->amount;
            }
            $loot_slots[$slot] = $loot_types;
        }

        return view('loot.edit')->with(array('loot' => $loot_slots, 'adventures' => $adventures, 'useradventure' => $user_adventure));
    }

    /**
     * @param $user_adventure_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($user_adventure_id)
    {
        //if for some reason the person posting isn't logged in, bail!
        $this->user->redirectNonAuthedUser();

        //Check it's an admin or it's the same user.
        $user_adventure = $this->loot->byId($user_adventure_id);
        if (!$this->user->isAdmin() && !$this->user->getUser()->id === $user_adventure->User->id)
            return Redirect::to('/')->with('error', 'Sorry you do not have permission to do this!');

        $data = Input::all();
        $data['user_id'] = $this->user->getUser()->id;
        $data['user_adventure_id'] = $user_adventure_id;
        $v = Validator::make($data, $this->rules());
        if ($v->passes()) {
            $this->loot->update($data);
            return Redirect::to('loot')->with(array('success' => 'Loot updated successfully.'));
        } else {
            return Redirect::to('loot/'.$user_adventure_id.'/edit')->withInput()->withErrors($v->errors());
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $adventures = $this->adventure->all();
        if (old('adventure_id')) {

            $adventure = $this->adventure->byId(old('adventure_id'));

            $loot = array();
            for ($slot = 1; $slot <= 20; $slot++) {
                $lootTypes = array();
                if ($adventure->loot()->slot($slot)->count() > 0)
                    $lootTypes[0] = "Please select loot.";
                foreach ($adventure->loot()->slot($slot) as $lootSlot) {
                    $lootTypes[$lootSlot->id] = $lootSlot->type . ' - '.$lootSlot->amount;
                }
                if (!empty($lootTypes))
                    $loot[$slot] = $lootTypes;
            }

            return view('loot.create', compact('adventures', 'loot'));
        } else {
            return view('loot.create', compact('adventures'));
        }
    }

    /**
     * @param $adventure_id
     * @return \Illuminate\View\View
     */
    public function create_form($adventure_id)
    {
        $adventure = $this->adventure->findAdventureWithLoot($adventure_id);
        return view('loot.create_form', $adventure);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $v = Validator::make(Input::all(), $this->rules());

        if ($v->passes()) {
            $data = Input::all();
            $data['user_id'] = $this->user->getUser()->id;
            $this->loot->create($data);
            return Redirect::to('loot/create')->with(array('success' => 'Loot added successfully, <a href="/loot">click here to see your latest loot.</a>'));
        } else {
            return Redirect::to('loot/create')->withInput()->withErrors($v->errors());
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        //Check that the loot exists.
        $loot = $this->getLootById($id, 'Loot not found.');
        if (($this->user->getUser()->id === $loot->User->id) || ($this->user->isAdmin())) {
            $this->loot->delete($id);
            return Redirect::back()->with(array('success' => 'Loot deleted.'));
        } else {
            return Redirect::back()->with(array('error' => 'You do not have sufficient permissions.'));
        }
    }

    /**
     * @return mixed
     */
    public function getLootForAdventure()
    {
        $data = Input::all();
        $result = $this->adventure->byId($data["adventure"])->loot()->orderBy('slot')->orderBy('type')->orderBy('amount')->get();
        return $result;
    }


    /**
     * @return array
     */
    public function rules() {
        //Check what slots the given adventure has and add those to the rules.
        $adventureRepo = App::make('LootTracker\Repositories\Adventure\AdventureInterface');
        $adventure = $adventureRepo->byId(Input::get('adventure_id'));
        $rules = [];
        for ($slot = 1; $slot < 30; $slot++) {
            $rules = array_except($rules, 'slot' . $slot); //Not sure it's needed, but added just in case.
            if ($adventure->loot()->slot($slot)->count() > 0)
                $rules = array_add($rules, 'slot' . $slot, 'required|exists:adventure_loot,id,slot,' . $slot);
        }

        return $rules;
    }

    /**
     * @param $loot_id
     * @param string $message
     * @param string $redirect_to
     * @return mixed
     */
    private function getLootById($loot_id, $message, $redirect_to = '')
    {
        //Get the loot or return an error.
        try {
            return $this->loot->byId($loot_id);
        } catch (ModelNotFoundException $ex) {
            if ($redirect_to !== '')
                return Redirect::to($redirect_to)->with(array('error' => $message));
            else
                return Redirect::back()->with(array('error' => $message));
        }
    }
}