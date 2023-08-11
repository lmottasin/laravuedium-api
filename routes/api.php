<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\LogoutController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function (){
    Route::post('auth/logout', LogoutController::class);

    Route::get('user', function (Request $request){
        return $request->user();
    });
});

Route::post('auth/register', RegisterController::class);
Route::post('auth/login', LoginController::class);
