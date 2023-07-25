<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;

use Illuminate\Support\Facades\Route;

//ログインしていない状態
Route::middleware('guest')->group(function () {
    //ユーザー登録
    Route::get('registration', [AuthController::class, 'toRegistrationForm'])
                ->name('to.registration.form');
    Route::post('register', [AuthController::class, 'createUser'])
                ->name('register');
    //ログイン
    Route::get('login', [AuthController::class, 'toLoginForm'])
                ->name('to.login.form');
    Route::post('login', [AuthController::class, 'login'])
                ->name('login');
    //ログアウト完了
    Route::get('logout-complete', function () {return view('auth.logout-complete');});
    //退会完了
    Route::get('delete-account-complete', function () {return view('auth.delete-account-complete');});
});

//ログインしている状態
Route::middleware('auth')->group(function () {
    //ログアウト
    Route::post('logout', [AuthController::class, 'logout'])
                ->name('logout');
    //退会
    Route::get('delete-account-confirm', [AuthController::class, 'toDeleteAccountConfirm'])
                ->name('to.delete.account.confirm');
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount'])
                ->name('delete.account');
});
