<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class ToppageController extends Controller
{
    public function index()
    {
        $data = [];
        
        //セッションを取得
        $value = session()->get('key');
        
        //セッションの値がnullかどうかで振り分け
        if(is_null($value)) {
        
        $request = null;
        $recipe_count = null;
        
        //すべてのレシピを作成日時の降順で取得
        $recipes = Recipe::orderBy('created_at', 'desc')->paginate(5);
        
        $data = [
            'request' => $request,
            'recipe_count' => $recipe_count,
            'recipes' => $recipes,
        ];
        
        } else {

        $recipes_ingredients = Recipe::select('recipes.*')
            ->leftJoinSub(
                Ingredient::select('recipe_id', 'ingredient_name'),
                'ingredients',
                'recipes.id',
                '=',
                'ingredients.recipe_id'
            )
            ->where('recipe_name', 'LIKE', "%$value%")
            ->orWhere('ingredient_name', 'LIKE', "%$value%")
            ->groupBy('recipes.id'); // レシピIDでグループ化
        
        $recipes = $recipes_ingredients
            ->orderBy('created_at', 'desc')->paginate(5);
        
        // 取得したデータの数をカウント
        $recipe_count = $recipes->total(); // ページネーション後の総数を取得

        $data = [
            'recipes' => $recipes,
            'recipe_count' => $recipe_count,
            'request' => $value,
        ];

        }
        
        return view('top', $data);
    }
    
    
    public function recipesFeed(Request $request)
    {
        //バリデーション
        $request->validate([
            'recipe_feed' => 'required',
        ]);
        
        //sessionデータほセット
        $request->session()->put('key', $request->recipe_feed);
        //セッションを取得
        $value = session()->get('key');
        
        $data = [];
        
        $recipes_ingredients = Recipe::select('recipes.*')
            ->leftJoinSub(
                Ingredient::select('recipe_id', 'ingredient_name'),
                'ingredients',
                'recipes.id',
                '=',
                'ingredients.recipe_id'
            )
            ->where('recipe_name', 'LIKE', "%$value%")
            ->orWhere('ingredient_name', 'LIKE', "%$value%")
            ->groupBy('recipes.id'); // レシピIDでグループ化
        
        $recipes = $recipes_ingredients
            ->orderBy('created_at', 'desc')->paginate(5);
        
        // 取得したデータの数をカウント
        $recipe_count = $recipes->total(); // ページネーション後の総数を取得
        
        $data = [
            'recipes' => $recipes,
            'recipe_count' => $recipe_count,
            'request' => $value,
        ];

        return view('top', $data);
    }

    public function recipesFeedDelete()
    {
        // 指定したデータを削除
        session()->forget('key');

        return Redirect::route('top');
    }

    //レシピを人気順で表示
    public function toTopPopular()
    {
        //空のデータを作っておく
        $data = [];
        
        //セッションを取得
        $value = session()->get('key');
        
        //セッションの値がnullかどうかで振り分け
        if(is_null($value)) {
        
        $request = null;
        $recipe_count = null;
         
        $fav_count =Favorite::select('recipe_id', Favorite::raw('COUNT(id) as favorites_count'))
                        ->groupBy('recipe_id');
             
        $favorites_count_order = Recipe::leftJoinSub($fav_count, 'fav_count', function ($join) {
                                        $join->on('recipes.id', '=', 'fav_count.recipe_id');
                                        })
                                        ->orderByRaw('favorites_count is null asc')
                                        ->orderby('favorites_count', 'desc')
                                        ->paginate(5);
        
        $data = [
            'recipes' => $favorites_count_order,
            'recipe_count' => $recipe_count,
            'request' => $value,
        ];
        
        } else {

            $recipes_ingredients = Recipe::select('recipes.*')
                ->leftJoinSub(
                    Ingredient::select('recipe_id', 'ingredient_name'),
                    'ingredients',
                    'recipes.id',
                    '=',
                    'ingredients.recipe_id'
                )
                ->where('recipe_name', 'LIKE', "%$value%")
                ->orWhere('ingredient_name', 'LIKE', "%$value%")
                ->groupBy('recipes.id'); // レシピIDでグループ化
             
            $fav_count = Favorite::select('recipe_id', Favorite::raw('COUNT(id) as favorites_count'))
                         ->groupBy('recipe_id');
                         
            $favorites_count_recipe = $recipes_ingredients->leftJoinSub($fav_count, 'fav_count', function ($join) {
                                            $join->on('recipes.id', '=', 'fav_count.recipe_id');
                                            })
                                        ->groupBy('recipes.id', 'favorites_count')
                                        ->orderByRaw('favorites_count is null asc')
                                        ->orderby('favorites_count', 'desc');
                                        
            $favorites_count_order = $favorites_count_recipe
                                        ->paginate(5);
            
            //取得したデータの数をカウント                                
            $recipe_count = $favorites_count_order
                            ->total();
             
            $data = [
                'recipes' => $favorites_count_order,
                'recipe_count' => $recipe_count,
                'request' => $value,
            ];
        }

        return view('top-popular', $data);
    }

    public function recipesFeedPopular(Request $request)
    {
        //バリデーション
        $request->validate([
            'recipe_feed' => 'required',
        ]);
        
        //sessionデータほセット
        $request->session()->put('key', $request->recipe_feed);
        //セッションを取得
        $value = session()->get('key');
        
        //空のデータを作っておく
        $data = [];
        
        $recipes_ingredients = Recipe::select('recipes.*')
            ->leftJoinSub(
                Ingredient::select('recipe_id', 'ingredient_name'),
                'ingredients',
                'recipes.id',
                '=',
                'ingredients.recipe_id'
            )
            ->where('recipe_name', 'LIKE', "%$value%")
            ->orWhere('ingredient_name', 'LIKE', "%$value%")
            ->groupBy('recipes.id'); // レシピIDでグループ化
        
        $fav_count = Favorite::select('recipe_id', Favorite::raw('COUNT(id) as favorites_count'))
                     ->groupBy('recipe_id');
                     
        $favorites_count_recipe = $recipes_ingredients->leftJoinSub($fav_count, 'fav_count', function ($join) {
                                        $join->on('recipes.id', '=', 'fav_count.recipe_id');
                                        })
                                    ->groupBy('recipes.id', 'favorites_count')
                                    ->orderByRaw('favorites_count is null asc')
                                    ->orderby('favorites_count', 'desc');
                                    
        $favorites_count_order = $favorites_count_recipe
                                    ->paginate(5);
        
        //取得したデータの数をカウント                                
        $recipe_count = $favorites_count_order
                        ->total();
        
        $data = [
            'recipes' => $favorites_count_order,
            'recipe_count' => $recipe_count,
            'request' => $value,
        ];

        return view('top-popular', $data);
    }

    public function recipesFeedPopularDelete()
    {
        // 指定したデータを削除
        session()->forget('key');

        return Redirect::route('to.top.popular');
    }
}
