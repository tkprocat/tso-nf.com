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
     * @return mixed
     */
    public function check();
}
