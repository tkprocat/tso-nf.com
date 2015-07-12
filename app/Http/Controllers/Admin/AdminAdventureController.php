<?php namespace LootTracker\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use LootTracker\Http\Requests\AdventureRequest;
use LootTracker\Repositories\Adventure\Admin\AdminAdventureInterface;
use LootTracker\Repositories\User\UserInterface;

/**
 * Class AdminAdventureController
 * @package LootTracker\Http\Controllers
 */
class AdminAdventureController extends Controller
{

    /**
     * @var AdminAdventureInterface
     */
    protected $adminAdventureRepo;

    /**
     * @var UserInterface
     */
    protected $userRepo;


    /**
     * @param AdminAdventureInterface $adminAdventure
     * @param UserInterface           $user
     */
    public function __construct(AdminAdventureInterface $adminAdventure, UserInterface $user)
    {
        $this->adminAdventureRepo = $adminAdventure;
        $this->userRepo = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $adventures = $this->adminAdventureRepo->all();

        return view('admin.adventure.index')->with('adventures', $adventures);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.adventure.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param AdventureRequest $request
     * @return \Illuminate\View\View
     */
    public function store(AdventureRequest $request)
    {
        //Passed validation, store the blog post.
        $this->adminAdventureRepo->create($request->all());

        return Redirect::to('admin/adventures')->with('success', 'Adventure added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $adventure = $this->adminAdventureRepo->findAdventureById($id);

        return view('admin.adventure.show')->with(array('adventure' => $adventure));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $adventure = $this->adminAdventureRepo->findAdventureById($id);
        if (is_null($adventure)) {
            return Redirect::to('admin/adventures')->with('error', 'Adventure not found!');
        }

        return view('admin.adventure.edit')->with('adventure', $adventure);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param AdventureRequest $request
     * @param $id
     * @return \Illuminate\View\View
     */
    public function update(AdventureRequest $request, $id)
    {
        $adventure = $request->all();
        $this->adminAdventureRepo->update($id, $adventure);
        return Redirect::to('admin/adventures')->with('success', 'Adventure updated successfully');
    }

    /**
     * Returns all available item types.
     */
    public function getItemTypes()
    {
        return $this->adminAdventureRepo->getItemTypes();
    }

    /**
     * Returns all available adventure types.
     */
    public function getAdventureTypes()
    {
        return $this->adminAdventureRepo->getAdventureTypes();
    }
}
