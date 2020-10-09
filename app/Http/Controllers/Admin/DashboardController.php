<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function __construct()                   // yo constructor pani hami aafaile banako
    {
        $this->middleware('auth:admin');             // admin guard ko base ma authentication garauna ko lagi
    }


    public function index()
    {
        return view('admin.dashboard');
    }

}
