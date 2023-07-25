<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\RenameUserController;
use App\Http\Controllers\RenameEmailController;
use App\Http\Controllers\RenamePassController;

use Illuminate\Support\Facades\Route;

//ログインしていない状態

//ログインしている状態
Route::middleware('auth')->group(function () {
    //ユーザーレシピ一覧ページを表示
    Route::get('/account-information/{id}', [AccountController::class, 'toAccountInformation'])
        ->name('account.information');
    //ユーザーレシピお気に入り一覧ページを表示
    Route::get('/account-information-favorite/{id}', [AccountController::class, 'toAccountInformationFavorite'])
        ->name('account.information.favorite');

    //ユーザーイメージアップデート処理
    Route::patch('/account-image-update/{id}', [AccountController::class, 'accountImageUpdate'])
        ->name('account.image.update');

    //ユーザー情報ページを表示
    Route::get('registration-info', function () {return view('account.registration-info');})
        ->name('account.registration.info');

    //ユーザー名変更
    Route::get('account-rename', [AccountController::class, 'toAccountRename'])
        ->name('account.rename');
    Route::patch('account-name-update', [AccountController::class, 'accountNameUpdate'])
        ->name('account.name.update');

    //メールアドレス変更
    Route::get('email-rename', [AccountController::class, 'toEmailRename'])
        ->name('email.rename');
    Route::patch('email-update', [AccountController::class, 'emailUpdate'])
        ->name('email.update');

    //パスワード変更
    Route::get('pass-rename', [AccountController::class, 'toPassRename'])
        ->name('pass.rename');
    Route::patch('pass-update', [AccountController::class, 'passUpdate'])
        ->name('pass.update');
});
