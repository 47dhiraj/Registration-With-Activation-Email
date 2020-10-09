<?php

namespace App\Http\Controllers\Auth\Admin;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\ActivationCodeEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RedirectsUsers;

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/admin/dashboard/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],          //modified gareko
            'last_name' => ['required', 'string', 'max:255'],           //modified gareko
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],                    //modified gareko
            'last_name' => $data['last_name'],                      //modified gareko
            
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        
        //Insert Code into Table
        $user->userActivationCode()->create([
            'code'=> Str::random(128)
        ]);
        


        //logout the user
        $this->guard('admin')->logout();
        //Auth::logout($user);
        


    
        // ActivationCodeEvent class ko constructro lai call gareko cha & also passing current registered user
        event(new ActivationCodeEvent($user));                   // ActivationCodeEvent class ko constructor lai hit gareko cha yo line le
        
      

        // email the user
        //Mail::to($user)->send(new ActivationEmail($code));                      // yo line ko $code vanna le... ActivationEmail pathauda activation code jaus vanna ko lagi .... yaha batw pathayeko $code chai ActivationEmail.php vanni page ko constructor as a object jasari receive hunxa
        //Mail::to($user)->queue(new ActivationEmail($code));                         // Yedi globally email lai queue garna chaina rather register garda matra ko email lai queueing garnu cha vani yesari garna sakincha.
        
        

        
        //redirect the user
        
        return redirect('/admin/login')->with('status', 'Activation link sent to your email. Please check after some time.');
        
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.admin.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new Response('', 201)
                    : redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');             // yaha pani guard user rakheko hamile
    }


}
