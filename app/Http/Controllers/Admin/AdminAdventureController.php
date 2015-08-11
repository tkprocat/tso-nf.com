<?php namespace LootTracker\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use LootTracker\Http\Requests\AdventureRequest;
use LootTracker\Repositories\Adventure\Admin\AdminAdventureInterface;
use LootTracker\Repositories\Item\ItemInterface;
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
     * @var ItemInterface
     */
    protected $itemRepo;


    /**
     * @param AdminAdventureInterface $adminAdventure
     * @param ItemInterface           $item
     * @param UserInterface           $user
     */
    public function __construct(AdminAdventureInterface $adminAdventure, ItemInterface $item, UserInterface $user)
    {
        $this->adminAdventureRepo = $adminAdventure;
        $this->itemRepo = $item;
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
        $items = $this->itemRepo->all();
        return view('admin.adventure.create')->with('items', $items);
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
        try {
            $adventure = $this->adminAdventureRepo->findAdventureById($id);
        } catch(ModelNotFoundException $ex) {
            return Redirect::to('admin/adventures')->with('error', 'Adventure not found!');
        }

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
        try {
            $adventure = $this->adminAdventureRepo->findAdventureById($id);
        } catch(ModelNotFoundException $ex) {
            return Redirect::to('admin/adventures')->with('error', 'Adventure not found!');
        }
        $items = $this->itemRepo->all();

        return view('admin.adventure.edit')->with(array('adventure' => $adventure, 'items' => $items));
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
}
