<?php

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


Route::get('/register',function() {
    return view('register');
});
Route::post('/user/register',\App\Http\Controllers\UserApiController::class."@postRegister" );
Route::post('/user/login',\App\Http\Controllers\UserApiController::class."@login" );

Route::get('/users',\App\Http\Controllers\UserApiController::class."@getUsers");
Route::get('/user/{id}',\App\Http\Controllers\UserApiController::class."@getUser");

Route::get('/ads', function (){
    $ads = \App\Models\Ad::all();
    return view('pages.adsView',["ads"=> $ads]);
});

Route::post('/tokens/create', function (\Illuminate\Support\Facades\Request $request) {


});
