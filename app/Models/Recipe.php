<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'recipe_image',
        'recipe_name',
    ];
    
    //Userモデルとの関係を定義し、このユーザーが所有する投稿とわかるようにする
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    //投稿をお気に入り登録したユーザー。（Recipeモデルとの関係を定義）
    public function favoriting_users()
    {
        //$recipe->favoriting_usersで$recipeをお気に入り登録しているユーザー達を取得できる。
        //関係先のModelのクラス(Recipe::class)を第一引数に指定、第二引数に中間テーブル(favorites)を指定
        //第三引数には中間テーブルに保存されている自分のidを示すカラム名(recipe_id)を、第四引数には中間テーブルに保存されている関係先のidを示すカラム名(user_id)を指定
        return $this->belongsToMany(User::class, 'favorites', 'recipe_id', 'user_id')->withTimestamps();
    }
    
    //レシピに対してのユーザーのコメント。（Recipeモデルとの関係を定義）
    public function comment_users()
    {
        //$recipe->comment_usersで$recipeにコメントしているユーザー達を取得できる。
        //関係先のModelのクラス(Recipe::class)を第一引数に指定、第二引数に中間テーブル(favorites)を指定
        //第三引数には中間テーブルに保存されている自分のidを示すカラム名(recipe_id)を、第四引数には中間テーブルに保存されている関係先のidを示すカラム名(user_id)を指定
        return $this->belongsToMany(User::class, 'comments', 'recipe_id', 'user_id')->withTimestamps();
    }

    //一対多
    //Ingredientモデルとの関係を定義し、このレシピが所有する食材とわかるようにする
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    //一対多
    //CookingProcessモデルとの関係を定義し、このレシピが所有する料理工程とわかるようにする
    public function cooking_processes()
    {
        return $this->hasMany(CookingProcess::class);
    }
}
