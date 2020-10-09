<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)                      // user authenticated or login session authenticated nai xa vani.. yo handle vitra ko kura run hune gardacha.... yaha pani hamile hamilai jasto khalko chaincha testai gari modify gareko cha
    {
        if (Auth::guard($guard)->check()) 
        {
            if( $guard == 'admin' )
            {
                return redirect('/admin/dashboard');
            }
            else if( $guard == 'user' )
            {
                return redirect('/user/dashboard');
            }


            //return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
