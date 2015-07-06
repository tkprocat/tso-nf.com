<?php namespace LootTracker\Http\Controllers\Auth;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use LootTracker\Repositories\User\UserInterface;
use Mail;
use Session;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LootTracker\Http\Controllers\Controller;
use LootTracker\Repositories\User\Role;
use LootTracker\Repositories\User\User;

class AuthController extends Controller
{
    /**
     * We don't want to use the default /home redirect, but use /blog instead.
     */
    protected $redirectTo = '/blog';

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    | Activation system taken from https://github.com/iateadonut/laravel51-email-authentication/
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @internal param Guard $auth
     * @internal param Registrar $registrar
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout', 'resendEmail', 'activateAccount']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return redirect()->intended($this->redirectPath());
        }

        return redirect($this->loginPath())
            ->withInput($request->only('username', 'remember'))
            ->withErrors([
                'username' => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|min:8',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $user = new User;
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = \Hash::make($request->input('password'));
        $user->activation_code = str_random(60);

        if ($user->save()) {
            $user->attachRole(Role::whereName('user')->firstOrFail());
            $this->sendEmail($user);
            return view('auth.activate')->with('email', $request->input('email'));
        } else {
            Session::flash('message', trans('notCreated'));
            return redirect()->back()->withInput();
        }
    }

    private function sendEmail(User $user)
    {
        $data = array(
            'username' => $user->username,
            'code' => $user->activation_code,
        );

        Mail::queue('emails.activate', $data, function ($message) use ($user) {
            $message->subject(trans('auth.pleaseActivate'));
            $message->to($user->email);
        });
    }

    public function getResend()
    {
        $user = Auth::user();
        if ($user->resent >= 3) {
            return view('auth.tooManyEmails')->with('email', $user->email);
        } else {
            $user->resent = $user->resent + 1;
            $user->save();
            $this->sendEmail($user);
            return view('auth.activateAccount')->with('email', $user->email);
        }
    }

    public function getActivate($code, UserInterface $user)
    {
        try {
            $user = $user->byActivationCode($code);

            $user->activated = 1;
            $user->save();

            Auth::login($user);
            Session::flash('message', trans('auth.successActivated'));
            return redirect('blog');

        } catch(ModelNotFoundException $ex) {
            Session::flash('message', trans('auth.unsuccessful'));
            return redirect('blog');
        }
    }
}
