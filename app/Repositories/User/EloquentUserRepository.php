<?php namespace LootTracker\Repositories\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

/**
 * Class EloquentUserRepository
 * @package LootTracker\Repositories\User
 */
class EloquentUserRepository implements UserInterface
{

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return User::with('guild')->get();
    }


    /**
     * @param $code
     *
     * @return mixed
     */
    public function byActivationCode($code)
    {
        return User::whereActivationCode($code)->firstOrFail();
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function byId($id)
    {
        return User::where('id', $id)->firstOrFail();
    }


    /**
     * @param $name
     *
     * @return mixed
     */
    public function byUsername($name)
    {
        return User::where('username', $name)->firstOrFail();
    }


    /**
     * @return bool
     */
    public function check()
    {
        return \Auth::check();
    }


    /**
     * @return User|null
     */
    public function getUser()
    {
        return \Auth::user();
    }


    /**
     * @return bool
     */
    public function isAdmin()
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->getUser()->hasRole('admin');
    }


    /**
     * @param $user
     */
    public function login($user)
    {
        Auth::login($user);
    }


    /**
     * @param $itemsPerPage
     *
     * @return mixed
     */
    public function paginate($itemsPerPage)
    {
        return User::orderBy('username')->paginate($itemsPerPage);
    }


    /**
     * @return null
     */
    public function redirectNonAuthedUser()
    {
        if (!Auth::check()) {
            return Redirect::to('login');
        }

        return null;
    }
}
