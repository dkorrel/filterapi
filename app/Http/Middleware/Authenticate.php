<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $input = $request->input();

        if( isset($input['token']) ) $this->authenticate($input['token']);

        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return response('Unauthorized.', 401);
            }
        }

        return $next($request);
    }

    protected function authenticate($token = null)
    {
        if(! $token ) return null;
        
        $users = User::all();

        $found = null;

        foreach( $users as $user )
        {
            if( isset($user->api_token) && $user->api_token === $token ) 
            {
                $found = $user;
            }
        }
        // dd(get_class_methods(Auth::guard('api')));
        if( $found ) Auth::guard('api')->setUser($found);

        return $found;
    }
}
