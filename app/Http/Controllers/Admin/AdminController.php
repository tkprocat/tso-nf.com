<?php namespace LootTracker\Http\Controllers;

use LootTracker\Repositories\Adventure\Admin\AdminAdventureInterface;
use LootTracker\Repositories\User\UserInterface;

class AdminController extends Controller
{

    protected $adminAdventure;
    protected $user;

    function __construct(AdminAdventureInterface $adminAdventure, UserInterface $user)
    {
        $this->adminAdventure = $adminAdventure;
        $this->user = $user;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $userCount = count($this->user->all());
        $registeredAdventures = $this->adminAdventure->all()->count();

        return view('admin.index', compact('userCount', 'registeredAdventures'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
