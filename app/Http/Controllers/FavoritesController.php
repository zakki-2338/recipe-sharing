<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class FavoritesController extends Controller
{
    //投稿をお気に入り登録するアクション
    public function recipeFavorite($id)
    {
        // 認証済みユーザ（閲覧者）が、 idの投稿をお気に入り登録する
        // Userモデルからfavoriteメソッドを持ってくる
        Auth::user()->favorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }

    //お気に入り登録を削除するアクション
    public function recipeUnfavorite($id)
    {
        // 認証済みユーザ（閲覧者）が、 idの投稿のお気に入り登録を削除する
        Auth::user()->unfavorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
}
