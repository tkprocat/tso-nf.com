<?php namespace LootTracker\Http\Controllers;

use LootTracker\Repositories\User\UserInterface;

/**
 * Class AdminUserController
 * @package LootTracker\Http\Controllers
 */
class AdminUserController extends Controller
{

    /**
     * @var UserInterface
     */
    protected $user;


    /**
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = $this->user->all();

        return view('admin.users.index')->with('users', $users);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = $this->user->byId($id);

        return view('admin.users.show')->with('user', $user);
    }
}
