<?php namespace LootTracker\Http\Controllers\Auth;

use Auth;
use Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use LootTracker\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;


class PasswordController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

	use ResetsPasswords;

    /**
     * We don't want to use the default /home redirect, but use /blog instead.
     */
    protected $redirectTo = '/blog';

    /**
     * Create a new password controller instance.
     *
     * @internal param Guard $auth
     * @internal param PasswordBroker $passwords
     */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'postChange']);
	}

    /**
     * Reset the given user's password.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function postChange(Request $request)
    {
        $this->validate($request, [
            'password_old' => 'required',
            'password' => 'required|confirmed',
        ]);

        $credentials['id'] = Auth::id();
        $credentials['password'] = $request->get('password_old');

        //Check the old password matches what we have stored for the user.
        if (!Auth::validate($credentials)) {
            return Redirect::back()->withErrors('Incorrect old password.');
        }

        //Reset the password
        $this->resetPassword(Auth::user(), $request->get('password'));

        return Redirect::back()->with('success', 'Password changed.');
    }
}
