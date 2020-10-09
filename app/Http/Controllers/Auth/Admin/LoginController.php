<?php

namespace App\Http\Controllers\Auth\Admin;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\User;                                   // User ko kura tala chaine vayeko vayera, hamile user lai pani use gareko

// use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use RedirectsUsers, ThrottlesLogins;
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');                     //yo line ma guest pachadi  :admin pani thapeko
    }


    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.admin.login');                                      // yo line pai modify gareko ho
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        //dd( request()->all() );  

        $this->validateLogin($request);


        try                                 // yo try catch pani hamile exception handle garna rakheko
        {
            $user = User::where('email', request()->email)->first(); 
            // if( $user->role_id == 2)
            if( $user->role->nickname == 'user')
            {
                return redirect('/login');
            }
        } 
        catch (Throwable $e) 
        {
            return redirect('/login');
        }

        //dd( request()->all() );                                      // yo line samma admin login form batw haleko email and password aako cha ki nai vanera check garna ko lagi dump and die gareko

        // $user = User::where('email', request()->email)->first(); 
        //dd( $user );
        //dd( $user->role_id );
        // if( $user->role_id == 2)
        // {
        //     return redirect('/login');
        // }


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        // dd(' Admin Logged in !');                    // actullay or exactly admin chai login vai rako cha ki nai vanera check gareko... so die and dump gareko

        return $this->guard('admin')->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard('admin')->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new Response('', 204)
                    : redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //dd($user);

        if(!$user->userIsActivated())               // yedi login form ma haleko user activated chaina vani vanna khojeko
        {
            $this->guard('admin')->logout(); 
            //Auth::logout($user);
            
            
            //dd($user);
            // return redirect('/login')->with('error', 'Your are not active, need code ? Click Here <a href="'. route('code.resend') .'?email='. $user->email.'">Resend Code</a>');
            // return redirect('/login')->with('error', 'Your account is not active. Please, check your email to activate.');

            return redirect('/admin/login')->with('error', $user->email);
        }


    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard('admin')->logout();                         // yedi guard chai vani chai yesari logout gareko ramro huncha
        //Auth::logout($request);   


        // $request->session()->invalidate();                   // yo line lai hamile comment kina haneko vanda, yedi autai browser ma admin and user login xa vani... suppose admin logout garda user wala account pani logout nahos.. just admin wala account logout vaos..

        // $request->session()->regenerateToken();

        // if ($response = $this->loggedOut($request)) {
        //     return $response;
        // }

        // return $request->wantsJson()
        //     ? new Response('', 204)
        //     : redirect('/admin/login');

        return redirect('/admin/login');                              // hamile modify gareko
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');                          // hamile yaha yesari 'user' guard apply gareko ho
    }


}
