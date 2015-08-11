<?php namespace LootTracker\Http\Controllers;

use Hash;
use LootTracker\Repositories\User\Role;
use Redirect;
use LootTracker\Repositories\User\UserInterface;
use Illuminate\Http\Request;

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
     * Display a listing of users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = $this->user->all();

        return view('admin.users.index')->with('users', $users);
    }


    /**
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = $this->user->byId($id);

        return view('admin.users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = $this->user->byId($id);

        return view('admin.users.edit')->with('user', $user);
    }

    /**
     * Reset the given user's password.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request, $id)
    {
        $this->validate($request, [
            'password'     => 'required|confirmed',
        ]);

        //Reset the password
        $user = $this->user->byId($id);
        $user->password = Hash::make($request->get('password'));
        $user->save();

        return Redirect::to('admin/users')->with('success', 'Password changed for '.$user->username.'.');
    }

    /**
     * Reset the given user's email.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'username'  => 'required',
            'email'     => 'required',
        ]);

        //Reset the password
        $user = $this->user->byId($id);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->activated = ($request->get('activated', '') === 'on');
        if ($request->get('prices_admin', '') === 'on')
        {
            //Add role if the user doesn't have it already.
            if (!$user->hasRole('prices_admin')) {
                $user->attachRole(Role::whereName('prices_admin')->first()); //Not pretty
            }
        } else {
            //Remove role if user has it.
            if ($user->hasRole('prices_admin')) {
                $user->detachRole(Role::whereName('prices_admin')->first()); //Not pretty
            }
        }

        $user->save();

        return Redirect::to('admin/users')->with('success', 'User updated.');
    }
}
