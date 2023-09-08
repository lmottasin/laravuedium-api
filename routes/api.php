<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\LogoutController;
use App\Http\Controllers\API\V1\Auth\PasswordUpdateController;
use App\Http\Controllers\API\V1\Auth\ProfileController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\Auth\ResendEmailVerificationController;
use App\Http\Controllers\API\V1\Auth\UserController;
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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['middleware' => 'verified'], function () {
        Route::apiSingleton('profile', ProfileController::class);
        Route::put('password', PasswordUpdateController::class)->name('password.update');
    });

    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('logout', LogoutController::class)->name('logout');
        Route::get('user', UserController::class)->name('user');
        Route::post('email/resend-verification-notification', ResendEmailVerificationController::class)
            ->name('verification.send');
    });

});

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class);
});

