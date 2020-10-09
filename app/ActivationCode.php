<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivationCode extends Model
{
    //
    protected $fillable = ['code'];         // code vanni column lai fillable garako

    public function user()                      // Yo chai RELATIONSHIP ho
    {
        return $this->belongsTo(User::class);

    }

    public function getRouteKeyName()               //getRouteKeyName() laravel ko inbuilt method ho        // laravel le kunai pani model ko lagi id by default heri rako huncha... so id by default model lai naheros rather aru kunai column le model lai heros vanni cha vani yo talako code lekhincha
    {
        return 'code';                  // now laravel relate this ActivationCode model by code column or field
    }
    

}
