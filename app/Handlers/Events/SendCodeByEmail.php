<?php

namespace App\Handlers\Events;

use App\Mail\ActivationEmail;                           // yo class lai use gareko cha so import garna parcha
use App\Events\ActivationCodeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;                // Mail class ko use gareko cha so yo class lai import garna parcha

class SendCodeByEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ActivationCodeEvent  $event
     * @return void
     */
    public function handle(ActivationCodeEvent $event)
    {
        Mail::to($event->user)->queue(new ActivationEmail($event->user->userActivationCode));           // yo line ko   user->userActivationCode  vaneko user ko ActivationCode class sanga ko relationship ho whic default return code not the id.
    }
}
