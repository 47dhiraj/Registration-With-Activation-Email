<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;                            // Request lai import garnu parcha

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    
    protected $redirectTo = '/user/dashboard/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:user')->except('logout');                  // yo line ma gues pachadi   :user pani thapeko
    }


    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)                           // AuthenticatesUsers trait ko authenticated() method lai override gareko yaha
    {
        //dd($user);

        if(!$user->userIsActivated())               // yedi login form ma haleko user activated chaina vani vanna khojeko
        {
            
            $this->guard('user')->logout();                 // yedi guard cha vani yesari logout gareko ramro huncha
            //Auth::logout($user);
            
            
            //dd($user);
            // return redirect('/login')->with('error', 'Your are not active, need code ? Click Here <a href="'. route('code.resend') .'?email='. $user->email.'">Resend Code</a>');
            // return redirect('/login')->with('error', 'Your account is not active. Please, check your email to activate.');

            return redirect('/login')->with('error', $user->email);
        }

        
    }

}
