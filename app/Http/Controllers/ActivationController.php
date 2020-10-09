<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\ActivationEmail;
use App\Events\ActivationCodeEvent;                 // yo event class use vako cha so yo class lai import gareko
use Illuminate\Support\Facades\Auth;                // yesari Auth vanni class lai pani import garnu pareko cha
use Illuminate\Support\Facades\Mail;                    // Mail class lai pani use gareko cha so import garna parcha
use Illuminate\Http\Request;                                // Request class pani use gareko cha so import garna parcha
use App\ActivationCode;                             // ActivationCode model lai yesari import garna parcha, yo activation vanni method ma ActivationCode model ko object i.e $code as paramerter pass gariyeko cha

class ActivationController extends Controller
{
    
    // public function activation(Request $request)
    // {
    //       dd($request);
    // }


    public function activation(ActivationCode $code)                                    // yaha chai $code vaneko as a model aai rako huncha.. or model  ko object ko rup ma            // yo activation method ma $code chai ActivationCode model ko object as a parameter pass gariyeko cha
    {

        $code->delete();                                                                // url batw or ma vayeko particular code ko ActivationCode ma vayeko record lai delete garcha


        // $code->user()        => yo vannale ActivationCode model ko sepecific record in relation with user() Model
        $code->user()->update([                                                         // tyo partcular ActivationCode ko relation ma vayeko particular user() model ko sepecific user ko active vanni column or field lai false batw true ma update garcha
            'active'=> true
        ]);

        //dd($code->user);
        
        if($code->user->role_id == 1)
        {
            return redirect('/admin/login')->with('activated', 'Your Account is Activated Successfully.');
        }

        if($code->user->role_id == 2)
        {
            return redirect('/login')->with('activated', 'Your Account is Activated Successfully.');
        }
         
    }



    public function coderesend(Request $request)
    {
        //dd($request);

        $user = User::whereEmail($request->email)->firstOrfail();

        if($user->userIsActivated())                            // userIsActivated() chai method ho jun chai User.php vanni model ma cha
        {
            if($user->role_id == 1)                            // userIsActivated() chai method ho jun chai User.php vanni model ma cha
            {
                return redirect('/admin/dashboard');
            }

            if($user->role_id == 2)                            // userIsActivated() chai method ho jun chai User.php vanni model ma cha
            {
                return redirect('/user/dashboard');
            }
        }


        Auth::logout($user);

        //Mail::to($user)->queue(new ActivationEmail($user->userActivationCode));                     // yo line ko userActivationCode  ko user model and ActivationCode model   bich ko relationship ho      //  $user->userActivationCode  yeti code le chai specific user ko userActivationCode model ko specific record lai as an object nikalcha
        //ActivationCodeEvent class ko constructro lai call gareko cha & also passing current registered user
        event(new ActivationCodeEvent($user));                   // ActivationCodeEvent class ko constructor lai hit gareko cha yo line le
        
        
        return redirect('/login')->with('status', 'Your code has been sent, please check your email.');
    }
    

}
