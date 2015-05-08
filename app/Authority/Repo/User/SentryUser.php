<?php namespace Authority\Repo\User;

use Mail;
use Cartalyst\Sentry\Sentry;
use Authority\Repo\RepoAbstract;

class SentryUser extends RepoAbstract implements UserInterface {
	
	protected $sentry;

	/**
	 * Construct a new SentryUser Object
	 */
	public function __construct(Sentry $sentry)
	{
		$this->sentry = $sentry;

		// Get the Throttle Provider
		$this->throttleProvider = $this->sentry->getThrottleProvider();

		// Enable the Throttling Feature
		$this->throttleProvider->enable();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($data)
	{
		$result = array();
		try {
            //Check if the email has been used.
            $model = $this->sentry->getEmptyUser();
            $user = $model->where('email', '=', e($data['email']))->first();
            if (isset($user)) {
                $result['success'] = false;
                $result['message'] = trans('users.emailexists');
                return $result;
            }


			//Attempt to register the user. 
			$user = $this->sentry->register(array('username' => e($data['username']), 'email' => e($data['email']), 'password' => e($data['password'])));

			//success!
	    	$result['success'] = true;
	    	$result['message'] = trans('users.created');
	    	$result['mailData']['activationCode'] = $user->GetActivationCode();
			$result['mailData']['userId'] = $user->getId();
            $result['mailData']['username'] = e($data['username']);
			$result['mailData']['email'] = e($data['email']);
		}
		catch (\Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.loginreq');
		}
		catch (\Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.exists');
		}

		return $result;
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  array $data
	 * @return Response
	 */
	public function update($data)
	{
		$result = array();
		try
		{
		    // Find the user using the user id
		    $user = $this->sentry->findUserById($data['id']);

		    // Update the user details
            if ($this->sentry->getUser()->hasAccess('admin'))
		        $user->username = e($data['username']);

		    // Only Admins should be able to change group memberships. 
		    $operator = $this->sentry->getUser();
		    if ($operator->hasAccess('admin'))
		    {
			    // Update group memberships
			    $allGroups = $this->sentry->getGroupProvider()->findAll();
			    foreach ($allGroups as $group)
			    {
			    	if (isset($data['groups'][$group->id])) 
	                {
	                    //The user should be added to this group
	                    $user->addGroup($group);
	                } else {
	                    // The user should be removed from this group
	                    $user->removeGroup($group);
	                }
			    }
			}

		    // Update the user
		    if ($user->save())
		    {
		        // User information was updated
		        $result['success'] = true;
	    		$result['message'] = trans('users.updated');
		    }
		    else
		    {
		        // User information was not updated
		        $result['success'] = false;
	    		$result['message'] = trans('users.notupdated');
		    }
		}
		catch (\Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.exists');
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.notfound');
		}

		return $result;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		try
		{
		    // Find the user using the user id
		    $user = $this->sentry->findUserById($id);

		    // Delete the user
		    $user->delete();
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    return false;
		}
		return true;
	}

	/**
	 * Attempt activation for the specified user
	 * @param  int $id   
	 * @param  string $code 
	 * @return bool       
	 */
	public function activate($id, $code)
	{
		$result = array();
		try
		{
		    // Find the user using the user id
		    $user = $this->sentry->findUserById($id);

		    // Attempt to activate the user
		    if ($user->attemptActivation($code))
		    {
		        // User activation passed
		        $result['success'] = true;
		        $url = route('login');
	    		$result['message'] = trans('users.activated', array('url' => $url));
		    }
		    else
		    {
		        // User activation failed
		        $result['success'] = false;
	    		$result['message'] = trans('users.notactivated');
		    }
		}
		catch (\Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.exists');
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.notfound');
		}
		return $result;
	}

	/**
	 * Resend the activation email to the specified email address
	 * @param  Array $data
	 * @return Response
	 */
	public function resend($data)
	{
		try {
            //Attempt to find the user. 
            //$user = $this->sentry->getUserProvider()->findById(e($data['email']));
            $model = $this->sentry->getEmptyUser();

            $user = $model->where('email', '=', e($data['email']))->first();

            if (!$user->isActivated())
            {
                //success!
            	$result['success'] = true;
	    		$result['message'] = trans('users.emailconfirm');
                $result['mailData']['username'] = $user->username;
	    		$result['mailData']['activationCode'] = $user->GetActivationCode();
                $result['mailData']['userId'] = $user->getId();
                $result['mailData']['email'] = e($data['email']);
            }
            else 
            {
                $result['success'] = false;
	    		$result['message'] = trans('users.alreadyactive');
            }

	    }
	    catch (\Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.exists');
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.notfound');
		}
	    return $result;
	}

	/**
	 * Handle a password reset rewuest
	 * @param  Array $data 
	 * @return Bool       
	 */
	public function forgotPassword($data)
	{
		$result = array();
		try
        {
            $model = $this->sentry->getEmptyUser();
            $user = $model->where('email', '=', e($data['email']))->first();

	        $result['success'] = true;
	    	$result['message'] = trans('users.emailinfo');
	    	$result['mailData']['resetCode'] = $user->getResetPasswordCode();
			$result['mailData']['userId'] = $user->getId();
			$result['mailData']['email'] = e($data['email']);
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.notfound');
		}
        return $result;
	}

	/**
	 * Process the password reset request
	 * @param  int $id   
	 * @param  string $code 
	 * @return Array
	 */
	public function resetPassword($id, $code)
	{
		$result = array();
		try
        {
	        // Find the user
	        $user = $this->sentry->getUserProvider()->findById($id);
	        $newPassword = $this->_generatePassword(12);

			// Attempt to reset the user password
			if ($user->attemptResetPassword($code, $newPassword))
			{
				// Email the reset code to the user
	        	$result['success'] = true;
		    	$result['message'] = trans('users.emailpassword');
		    	$result['mailData']['newPassword'] = $newPassword;
		    	$result['mailData']['email'] = $user->email;
 			}
			else
			{
				// Password reset failed
				$result['success'] = false;
				$result['message'] = trans('users.problem');
			}
        }
       catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.notfound');
		}
        return $result;
	}

	/**
	 * Process a change password request. 
	 * @return Array $data
	 */
	public function changePassword($data)
	{
		$result = array();
		try
		{
			$user = $this->sentry->getUserProvider()->findById($data['id']);        
		
			if ($user->checkHash(e($data['oldPassword']), $user->getPassword()))
			{
				//The oldPassword matches the current password in the DB. Proceed.
				$user->password = e($data['newPassword']);

				if ($user->save())
				{
					// User saved
					$result['success'] = true;
					$result['message'] = trans('users.passwordchg');
				}
				else
				{
					// User not saved
					$result['success'] = false;
					$result['message'] = trans('users.passwordprob');
				}
			} 
			else 
			{
		        // Password mismatch. Abort.
		        $result['success'] = false;
				$result['message'] = trans('users.oldpassword');
			}                                        
		}
		catch (\Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
			$result['success'] = false;
			$result['message'] = 'Login field required.';
		}
		catch (\Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.exists');
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.notfound');
		}
		return $result;
	}

	/**
	 * Suspend a user
	 * @param  int $id      
	 * @param  int $minutes 
	 * @return Array          
	 */
	public function suspend($id, $minutes)
	{
		$result = array();
		try
		{
		    // Find the user using the user id
		    $throttle = $this->sentry->findThrottlerByUserId($id);

		    //Set suspension time
            $throttle->setSuspensionTime($minutes);

		    // Suspend the user
		    $throttle->suspend();

		    $result['success'] = true;
			$result['message'] = trans('users.suspended', array('minutes' => $minutes));
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.notfound');
		}
		return $result;
	}

	/**
	 * Remove a users' suspension.
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function unSuspend($id)
	{
		$result = array();
		try
		{
		    // Find the user using the user id
		    $throttle = $this->sentry->findThrottlerByUserId($id);

		    // Unsuspend the user
		    $throttle->unsuspend();

		    $result['success'] = true;
			$result['message'] = trans('users.unsuspended');
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.notfound');
		}
		return $result;
	}

	/**
	 * Ban a user
	 * @param  int $id 
	 * @return Array     
	 */
	public function ban($id)
	{
		$result = array();
		try
		{
		    // Find the user using the user id
		    $throttle = $this->sentry->findThrottlerByUserId($id);

		    // Ban the user
		    $throttle->ban();

		    $result['success'] = true;
			$result['message'] = trans('users.banned');
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.notfound');
		}
		return $result;
	}

	/**
	 * Remove a users' ban
	 * @param  int $id 
	 * @return Array     
	 */
	public function unBan($id)
	{
		$result = array();
		try
		{
		    // Find the user using the user id
		    $throttle = $this->sentry->findThrottlerByUserId($id);

		    // Unban the user
		    $throttle->unBan();

		    $result['success'] = true;
			$result['message'] = trans('users.unbanned');
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $result['success'] = false;
	    	$result['message'] = trans('users.notfound');
		}
		return $result;
	}

	/**
	 * Return a specific user from the given id
	 * 
	 * @param  integer $id
	 * @return User
	 */
	public function byId($id)
	{
		try
		{
		    $user = $this->sentry->findUserById($id);
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    return false;
		}
		return $user;
	}

    /**
     * Return a specific user from the given id
     *
     * @param $username
     * @return User
     */
    public function byUsername($username)
    {
        try
        {
            $user = $this->sentry->findUserByLogin($username);
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            return false;
        }
        return $user;
    }

	/**
	 * Return all the registered users
	 *
	 * @return stdObject Collection of users
	 */
	public function all()
	{
		$users = $this->sentry->findAllUsers();

		foreach ($users as $user) {
			if ($user->isActivated()) 
    		{
    			$user->status = "Active";
    		} 
    		else 
    		{
    			$user->status = "Not Active";
    		}

    		//Pull Suspension & Ban info for this user
    		$throttle = $this->throttleProvider->findByUserId($user->id);

    		//Check for suspension
    		if($throttle->isSuspended())
		    {
		        // User is Suspended
		        $user->status = "Suspended";
		    }

    		//Check for ban
		    if($throttle->isBanned())
		    {
		        // User is Banned
		        $user->status = "Banned";
		    }
		}

		return $users;
	}

	/**
     * Generate password - helper function
     * From http://www.phpscribble.com/i4xzZu/Generate-random-passwords-of-given-length-and-strength
     *
     */
    private function _generatePassword($length=9) {
        // We'll check if the user has OpenSSL installed with PHP. If they do
        // we'll use a better method of getting a random string. Otherwise, we'll
        // fallback to a reasonably reliable method.
        if (function_exists('openssl_random_pseudo_bytes'))
        {
            // We generate twice as many bytes here because we want to ensure we have
            // enough after we base64 encode it to get the length we need because we
            // take out the "/", "+", and "=" characters.
            $bytes = openssl_random_pseudo_bytes($length * 2);

            // We want to stop execution if the key fails because, well, that is bad.
            if ($bytes === false)
            {
                throw new \RuntimeException('Unable to generate random string.');
            }

            return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
        }

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    /**
     * @return \Cartalyst\Sentry\Users\UserInterface
     */
    public function getUser() {
        return $this->sentry->getUser();
   }

    /**
     * @return int
     */
    public function getUserID() {
        $user = $this->sentry->getUser();
        if ($user == null)
            return 0;
        else
            return $this->sentry->getUser()->id;
    }

    /**
     *
     */
    public function redirectNonAuthedUser() {
        if (!$this->sentry->check()) {
            return Redirect::to('/login');
        }
        return;
    }

    /**
     * @param $user_id
     * @return bool
     */
    public function checkCurrentUserIs($user_id) {
        return ($this->getUserID() == $user_id);
    }

    /**
     * @return mixed
     */
    public function isAdmin() {
        return $this->hasAccess('admin');
    }

    /**
     * @return bool
     */
    public function check() {
        return $this->sentry->check();
    }

    /**
     * @param $user
     */
    public function login($user, $remember = false) {
        $this->sentry->login($user, $remember);
    }

    /**
     * See if a user has access to the passed permission(s).
     * Permissions are merged from all groups the user belongs to
     * and then are checked against the passed permission(s).
     *
     * If multiple permissions are passed, the user must
     * have access to all permissions passed through, unless the
     * "all" flag is set to false.
     *
     * @param  string|array  $permissions
     * @param  bool  $all
     * @return bool
     */
    public function hasAccess($permissions, $all = true)
    {
        return $this->getUser()->hasAccess($permissions, $all);
    }

    /**
     * See if a user has access to the passed permission(s).
     * Permissions are merged from all groups the user belongs to
     * and then are checked against the passed permission(s).
     *
     * If multiple permissions are passed, the user must
     * have access to all permissions passed through, unless the
     * "all" flag is set to false.
     *
     * @param  string|array  $permissions
     * @param  bool  $all
     * @return bool
     */
    public function hasPermission($permissions, $all = true)
    {
        return $this->getUser()->hasPermission($permissions, $all);

    }
    /**
     * Returns if the user has access to any of the
     * given permissions.
     *
     * @param  array  $permissions
     * @return bool
     */
    public function hasAnyAccess(array $permissions)
    {
        return $this->getUser()->hasAnyAccess($permissions);
    }
}
