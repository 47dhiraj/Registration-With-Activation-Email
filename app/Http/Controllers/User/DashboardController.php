<?php

namespace App\Http\Controllers\User;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //

    public function __construct()                   // yo constructor pani hami aafaile banako
    {
        $this->middleware('auth:user');             // user guard ko base ma authentication garauna ko lagi
    }


    public function index()
    {   
        return view('user.dashboard');
    }

}
