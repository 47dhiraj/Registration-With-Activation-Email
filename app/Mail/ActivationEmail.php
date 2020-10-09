<?php
// yo ActivationEmail.php vanni mailable class hamile command line batw command hanera banayeko ho.... i.e. php artisan make:mail ActivationEmail
namespace App\Mail;

use App\ActivationCode;                                        // yesari ActivationCode class lai pani import garna parcha
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;             // Everytime emaail pathauda queue vaus vanna ko lagi yo ShouldQueue vanni import garincha and yeslai mailable class le implements pani garcha
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

//class ActivationEmail extends Mailable implements ShouldQueue           // Global implementation of Queue to the Mailable class, yo web application ma vako harek email ma queue lagcha... so ahile hamilai testo garnu man chaina vani, RegisterController.php page ma email send garda queue use gare jasari pani garna sakchau(i.e register garda matra ko email lai queue garne )   // Yesari ActivationEmail mailable class le ShouldQueue lai implements pani garna parcha... so that every email u send is being queued
class ActivationEmail extends Mailable 
{
    use Queueable, SerializesModels;

    public $code;                           // $code lai public nai garauna parcha , so that it can be also accessed in user_activation.blade.php file    // yo $code vanni public property pani haami aafaile banayeko ho

    public $url;                            // yo $url chai user_activation.blade.php vanni page ma pani use vako huncha... so tyo page ma use garna ko lagi... as a public property declare gareko
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ActivationCode $code)            // yo line ko $code chai AcitvationCode vanni model or class ko object ho....or instance of ActivationCode class       // yo construcotr ma pani modify garna parcha
    {
        $this->code = $code;

        $this->url = route('user.activation', $this->code);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user_activation');
    }
}
