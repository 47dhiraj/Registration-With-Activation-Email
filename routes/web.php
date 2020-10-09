<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->namespace('Auth\Admin')->group(function(){
    //Authentication Routes for Admin........
    Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'LoginController@login');
    // Route::get('logout', 'LoginController@logout')->name('admin.logout');
    Route::post('logout', 'LoginController@logout')->name('admin.logout');

    // Forget Password Route For Admins.
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset')->name('admin.password.update');
});

Route::prefix('admin')->namespace('Admin')->group(function(){
    Route::get('dashboard', 'DashboardController@index');
});

Route::prefix('user')->namespace('User')->group(function(){
    Route::get('dashboard', 'DashboardController@index');
});


Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');


Route::get('/activate/{code}', 'ActivationController@activation')->name('user.activation');           // Yo chai Route Model Binding garna use vako route ho

Route::get('/resend/code', 'ActivationController@coderesend')->name('code.resend');