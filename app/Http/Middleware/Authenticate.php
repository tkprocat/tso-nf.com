<?php namespace LootTracker\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Redirect;

class Authenticate
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $this->auth->user();
        //Quick bail.
        if (is_null($user)) {
            return Redirect::to('auth/login');
        }

        //Check if any roles or permissions are required.
        $permissionRequired = $this->getPermissionRequired($request);
        $roles = $this->getRoleRequired($request);
        //Check if the user has the necessary permission.
        if (empty($roles) && empty($permissionRequired)) {
            return $next($request);
        }

        if ($user->can($permissionRequired)) {
            return $next($request);
        }


        if ($user->hasRole($roles)) {
            return $next($request);
        }

        //No permissions or roles found, throw permission error.
        abort('403');
    }

    /**
     * @param $request
     * @return array
     */
    private function getPermissionRequired($request)
    {
        $roles = [];
        $route = $request->route();
        $actions = $route->getAction();

        if (isset($actions['permission'])) {
            if (is_array($actions['permission'])) {
                return array_merge($roles, $actions['permission']);
            }
            $roles[] = $actions['permission'];
        }

        return $roles;
    }


    private function getRoleRequired($request)
    {
        $roles = [];
        $route = $request->route();
        $actions = $route->getAction();

        if (isset($actions['role'])) {
            if (is_array($actions['role'])) {
                return array_merge($roles, $actions['role']);
            }
            $roles[] = $actions['role'];
        }

        return $roles;
    }
}
