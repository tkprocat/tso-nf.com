<?php namespace LootTracker\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use LootTracker\Http\Requests;
use LootTracker\Repositories\User\UserInterface;

/**
 * Class UserController
 * @package LootTracker\Http\Controllers
 */
class UserController extends Controller
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
        $users = $this->user->paginate(25);
        return view('users.index')->with('users', $users);
    }

    /**
     * Display the specified resource.
     *
     * @return $this|\Illuminate\View\View
     */
    public function show($username)
    {
        try {
            $user = $this->user->byUsername($username);
        } catch (ModelNotFoundException $ex) {
            return redirect()->back()->withErrors('User not found.');
        }
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $username
     * @return $this|\Illuminate\View\View
     */
    public function edit($username)
    {
        try {
            $user = $this->user->byUsername($username);
        } catch (ModelNotFoundException $ex) {
            return redirect('/users')->withErrors('User not found.');
        }
        return view('users.edit', compact('user'));
    }
}
