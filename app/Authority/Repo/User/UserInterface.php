<?php namespace Authority\Repo\User;

/**
 * Interface UserInterface
 * @package Authority\Repo\User
 */
interface UserInterface {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($data);
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id);

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id);

	/**
	 * Return a specific user from the given id
	 * 
	 * @param  integer $id
	 * @return User
	 */
	public function byId($id);

    /**
     * Return a specific user from the given username
     *
     * @param $username
     * @return User
     */
    public function byUsername($username);

	/**
	 * Return all the registered users
	 *
	 * @return stdObject Collection of users
	 */
	public function all();

    /**
     * @return mixed
     */
    public function getUser();

    /**
     * Return the users id
     *
     * @return mixed
     */
    public function getUserID();

    /**
     * Redirect users to login if they are not auth'ed.
     *
     * @return mixed
     */
    public function redirectNonAuthedUser();

    /**
     * Checks if the given id matches the current user's id.
     *
     * @param $user_id
     * @return mixed
     */
    public function checkCurrentUserIs($user_id);

    /**
     * Check if the current user is an admin.
     *
     * @return mixed
     */
    public function isAdmin();

    /**
     * Checks if the user is logged in.
     *
     * @return mixed
     */
    public function check();


    /**
     * Used to log in the given user.
     *
     * @param $user
     * @return mixed
     */
    public function login($user, $remember = false);

    /**
     * See if a user has access to the passed permission(s).
     * Permissions are merged from all groups the user belongs to
     * and then are checked against the passed permission(s).
     *
     * If multiple permissions are passed, the user must
     * have access to all permissions passed through, unless the
     * "all" flag is set to false.
     *
     * Super users have access no matter what.
     *
     * @param  string|array  $permissions
     * @param  bool  $all
     * @return bool
     */
    public function hasAccess($permissions, $all = true);
    /**
     * See if a user has access to the passed permission(s).
     * Permissions are merged from all groups the user belongs to
     * and then are checked against the passed permission(s).
     *
     * If multiple permissions are passed, the user must
     * have access to all permissions passed through, unless the
     * "all" flag is set to false.
     *
     * Super users DON'T have access no matter what.
     *
     * @param  string|array  $permissions
     * @param  bool $all
     * @return bool
     */
    public function hasPermission($permissions, $all = true);
    /**
     * Returns if the user has access to any of the
     * given permissions.
     *
     * @param  array  $permissions
     * @return bool
     */
    public function hasAnyAccess(array $permissions);
}
