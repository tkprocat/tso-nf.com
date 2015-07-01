<?php namespace LootTracker\Repositories\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class EloquentUserRepository implements UserInterface
{

    public function all()
    {
        return User::with('guild')->get();
    }

    public function byActivationCode($code)
    {
        return User::whereActivationCode($code)->firstOrFail();
    }

    public function byId($id)
    {
        return User::where('id', $id)->firstOrFail();
    }

    public function byUsername($name)
    {
        return User::where('username', $name)->firstOrFail();
    }

    public function check()
    {
        return \Auth::check();
    }

    public function getUser()
    {
        return \Auth::user();
    }

    public function isAdmin()
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->getUser()->hasRole('admin');
    }

    public function login($user)
    {
        Auth::login($user);
    }

    public function paginate($itemsPerPage)
    {
        return User::orderBy('username')->paginate($itemsPerPage);
    }

    public function redirectNonAuthedUser()
    {
        if (!Auth::check()) {
            return Redirect::to('login');
        }
    }
}