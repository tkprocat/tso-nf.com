<?php namespace LootTracker\Repositories\User;

interface UserInterface
{
    public function all();

    public function byActivationCode($code);

    public function byId($id);

    public function byUsername($name);

    public function check();

    public function getUser();

    public function isAdmin();

    public function login($user);

    public function paginate($itemsPerPage);

    public function redirectNonAuthedUser();
}
