<?php

namespace App;

use App\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'role_id', 'email', 'password', 'active',             //modify gareko
    ];

    // protected $guarded = [];                 // yo mathi ko fillable ko alternative yesari guarded pani use  garna sakinxa

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    Public function role()                                  // Yo chai Relationship function ho
    {
        return $this->belongsTo( Role::class );
    }


    public function userActivationCode()                        // Yo chai RELATIONSHIP function ho
    {
        return $this->hasOne(ActivationCode::class);
    }


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $route = $this->role->nickname == 'admin' ? 'admin.password.reset' : 'password.reset';

        $this->notify(new ResetPasswordNotification($route, $token));                   // yo line ko ResetPasswordNotification  class lai aru class jasto sajilari right click garera import garna mildaina.. yeslai terminal ko  help batw garna parcha
    }



    public function userIsActivated()                   // yedi user active cha ki nai vanera hamilai bela bela ma function ko help batw tha pauna man cha vane... yesto model ma function pani banauna sakincha
    {
        if($this->active)                   // yo line ko $this vannale User class vanne janaucha (i.e user vanni janaucha)
        {
            return true;
        }
        return false;
    }

}
