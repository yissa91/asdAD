<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('/ads',\App\Http\Controllers\AdApiController::class);

Route::post('/users/login',App\Http\Controllers\UserApiController::class."@login");
Route::post('/users/logout',App\Http\Controllers\UserApiController::class."@logout")
    ->middleware("auth:sanctum");

Route::post('/users/signup',App\Http\Controllers\UserApiController::class."@signup");


Route::resource('/categories',App\Http\Controllers\CategoryController::class);



