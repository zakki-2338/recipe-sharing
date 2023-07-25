<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    //Recipeモデルとの関係を定義し、このユーザーが所有する投稿とわかるようにする
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
    
    //このユーザがお気に入り中の投稿。（Recipeモデルとの関係を定義）
    public function favoritings()
    {
        //$user->favoritingsで$userがお気に入り登録している投稿を取得できる。
        //関係先のModelのクラス(Recipe::class)を第一引数に指定、第二引数に中間テーブル(favorites)を指定
        //第三引数には中間テーブルに保存されている自分のidを示すカラム名(user_id)を、第四引数には中間テーブルに保存されている関係先のidを示すカラム名(recipe_id)を指定
        return $this->belongsToMany(Recipe::class, 'favorites', 'user_id', 'recipe_id')->withTimestamps();
    }
    
    
    //$recipeIdで指定された投稿をお気に入り登録する。
    public function favorite($recipeId)
    {
        //引数のレシピIdをすでにお気に入り登録しているか確認
        $exist = $this->is_favoriting($recipeId);
        
        //idからレシピ検索
        $recipe = Recipe::findOrFail($recipeId);
        
        //レシピのユーザーを検索
        $user_id = $recipe->user->id;
        
        //このレシピのuserIdが自分
        $its_mine = $user_id == Auth::user()->id;
        
        //すでにお気に入り登録しているもしくは、自分のレシピである場合、処理を実行
        if ($exist || $its_mine) {
            return false;
        } else {
            //この投稿をお気に入り中の投稿の仲間に入れる
            $this->favoritings()->attach($recipeId);
            return true;
        }
    }
    
    
    //$recipeIdで指定されたお気に入り投稿をお気に入りから外す
    public function unfavorite($recipeId)
    {
        //引数のレシピIdをすでにお気に入り登録しているか確認
        $exist = $this->is_favoriting($recipeId);
        
        //idからレシピ検索
        $recipe = Recipe::findOrFail($recipeId);
        
        //レシピのユーザーを検索
        $user_id = $recipe->user->id;
        
        //このレシピのuserIdが自分
        $its_mine = $user_id == Auth::user()->id;
        
        ////すでにお気に入り登録していて、かつ自分のレシピではない場合、処理を実行
        if ($exist && !$its_mine) {
            //この投稿をお気に入り中の投稿の仲間から外す
            $this->favoritings()->detach($recipeId);
            return true;
        } else {
            return false;
        }
    }
    
    
    //指定された$recipeIdの投稿をこのユーザがお気に入り登録中であるか調べる。お気に入り登録中ならtrueを返す。
    public function is_favoriting($recipeId)
    {
        //このユーザーのfavoritings()で、recipe_idカラムの中に引数と同じidがはいっているか確認
        //本来はwhere(第一, =, 第三)であるが=は省略できる
        //exists()は正しければtrue、違えばfalseを出す
        return $this->favoritings()->where('recipe_id', $recipeId)->exists();
    }
    
    //対象ユーザのお気に入り中投稿に絞り込む。
    public function feed_recipes()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $recipeIds = $this->favoritings()->pluck('recipes.id')->toArray();
        // それらのユーザが所有する投稿に絞り込む
        return Recipe::whereIn('id', $recipeIds);
    }
    
    //このユーザーのレシピに対するコメント。（Recipeモデルとの関係を定義）
    public function comments()
    {
        //$user->commentsでユーザーがしたコメントを取得できる。
        //関係先のModelのクラス(Recipe::class)を第一引数に指定、第二引数に中間テーブル(favorites)を指定
        //第三引数には中間テーブルに保存されている自分のidを示すカラム名(user_id)を、第四引数には中間テーブルに保存されている関係先のidを示すカラム名(recipe_id)を指定
        return $this->belongsToMany(Recipe::class, 'comments', 'user_id', 'recipe_id')->withTimestamps();
    }
}