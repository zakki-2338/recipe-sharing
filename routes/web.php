<?php

use App\Http\Controllers\ToppageController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\CommentsController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/app/{any?}', function() {
    return view('app');
});

Route::middleware('guest')->group(function () {
    Route::get('/', function () {return view('top');});
});

Route::middleware('auth')->group(function () {
    //ここでurlを指定                         ここで使用するviewファイルを指定 ここで使用した名前をhref内に入れる
    //トップページ(ログイン後)投稿を新着順で表示
    Route::get('top', [ToppageController::class, 'index'])
        ->name('top');
    //トップページレシピ検索
    Route::get('recipes-feed', [ToppageController::class, 'recipesFeed'])
        ->name('recipes.feed');
    //トップページレシピセッション削除
    Route::delete('recipes-feed-delete', [ToppageController::class, 'recipesFeedDelete'])
        ->name('recipes.feed.delete');
    //投稿をお気に入り順で表示
    Route::get('top-popular', [ToppageController::class, 'toTopPopular'])
        ->name('to.top.popular');
    //お気に入り順ページレシピ検索
    Route::get('recipes-feed-popular', [ToppageController::class, 'recipesFeedPopular'])
        ->name('recipes.feed.popular');
    //お気に入り順ページレシピセッション削除
    Route::delete('/recipes-feed-popular-delete', [ToppageController::class, 'recipesFeedPopularDelete'])
        ->name('recipes.feed.popular.delete');
    //レシピ投稿
    Route::get('recipe-post', [RecipeController::class, 'toRecipePost'])
        ->name('to.recipe.post');
    Route::post('recipe-post', [RecipeController::class, 'recipePost'])
        ->name('recipe.post');
    //レシピ詳細
    Route::get('recipe-detail/{id}', [RecipeController::class, 'toRecipeDetail'])
        ->name('to.recipe.detail');
    //レシピ編集
    Route::get('recipe-edit/{id}', [RecipeController::class, 'toRecipeEdit'])
        ->name('to.recipe.edit');
    Route::post('/recipe-update/{id}', [RecipeController::class, 'recipeUpdate'])
        ->name('recipe.update');
    //レシピ削除                 ここでidを渡している
    Route::delete('/recipe-delete/{id}', [RecipeController::class, 'recipeDelete'])
        ->name('recipe.delete');

    //recipes/{id}/urlになる
    Route::group(['prefix' => 'recipes/{id}'], function () {
        //レシピお気に入り登録
        Route::post('recipe-favorite', [FavoritesController::class, 'recipeFavorite'])
            ->name('recipe.favorite');
        //レシピお気に入り削除
        Route::delete('recipe-unfavorite', [FavoritesController::class, 'recipeUnfavorite'])
            ->name('recipe.unfavorite');

        //コメント
        Route::post('recipe-comment', [CommentsController::class, 'recipeComment'])
            ->name('recipe.comment');
        //コメント削除
        Route::delete('recipe-comment-delete', [CommentsController::class, 'recipeCommentDelete'])
            ->name('recipe.comment.delete');
    });       
});

//routesのauth.phpを使用することを宣言
require __DIR__.'/auth.php';
require __DIR__.'/account.php';
