<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Comment;
use App\Models\Ingredient;
use App\Models\CookingProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class RecipeController extends Controller
{
    //レシピ投稿用ページ表示
    public function toRecipePost(Request $request)
    {
        return view('recipes.recipe-post', [
            $user = Auth::user()
        ]);
        
    }

    //レシピ投稿処理
    public function recipePost(Request $request)
    {
        //バリデーション
        $request->validate([
            'recipe_name' => 'required|max:255',
            'recipe_image' => 'required|image',
            'ingredients_name' => 'required|array',
            'ingredients_name.*' => 'required|max:255',
            'ingredients_quantity' => 'required|array',
            'ingredients_quantity.*' => 'required|max:255',
            'cookingProcesses_process' => 'required|array',
            'cookingProcesses_process.*' => 'required|max:255',
        ]);

        //アップデートする画像を変数に代入
        $recipeImage = $request->file('recipe_image');
        
        //S3のimagesフォルダに、第二引数に指定した画像を保存する
        $path = Storage::disk('s3')->putFile('images', $recipeImage);
        
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $recipe = $request->user()->recipes()->create([
            'recipe_image' => $path,
            'recipe_name' => $request->recipe_name
        ]);

        $ingredients = $request->input('ingredients_name');
        $quantities = $request->input('ingredients_quantity');
    
        for ($i = 0; $i < count($ingredients); $i++) {
            $recipe->ingredients()->create([
                'ingredient_name' => $ingredients[$i],
                'ingredient_quantity' => $quantities[$i],
            ]);
        }

        $processes = $request->input('cookingProcesses_process');
    
        for ($i = 0; $i < count($processes); $i++) {
            $recipe->cooking_processes()->create([
                'process' => $processes[$i],
            ]);
        }

        //登録情報ページへ戻る
        return Redirect::route('top');
    }
    
    //レシピ詳細ページへ
    public function toRecipeDetail($id)
    {
        $data = [];
        
        //idの値で投稿を検索して取得
        $recipe = Recipe::findOrFail($id);
        $comments = Comment::where('recipe_id', $id)->orderBy('created_at', 'desc')->paginate(5);
        
        $data = [
            'recipe' => $recipe,
            'comments' => $comments,
        ];
        
        // レシピ詳細ビューでそれを表示
        return view('recipes.recipe-detail', $data);
    }

    //レシピ編集用ページ表示
    public function toRecipeEdit($id)
    {
        //idの値で投稿を検索して取得
        $recipe = Recipe::findOrFail($id);
        $ingredients = Ingredient::where('recipe_id', $id)->get();
        $cookingProcesses = CookingProcess::where('recipe_id', $id)->get();
        
        $data = [
            'recipeData' => $recipe,
            'ingredientsData' => $ingredients,
            'cookingProcessesData' => $cookingProcesses,
        ];

        // レシピ編集ビューでそれを表示    //第二引数は"['渡す先での変数名' => 今回渡す変数]"
        return view('recipes.recipe-edit', $data);
    }

    //レシピ更新動作確認用
    public function recipeUpdate(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'recipe_name' => 'required|max:255',
            'recipe_image' => 'nullable|image',
            'ingredients_name' => 'required|array',
            'ingredients_name.*' => 'required|max:255',
            'ingredients_quantity' => 'required|array',
            'ingredients_quantity.*' => 'required|max:255',
            'cookingProcesses_process' => 'required|array',
            'cookingProcesses_process.*' => 'required|max:255',
        ]);
    
        // レシピを取得
        $recipe = Recipe::findOrFail($id);
    
        // レシピ名を更新
        $recipe->recipe_name = $request->input('recipe_name');
    
        // 画像がアップロードされた場合は、画像を更新
        if ($request->hasFile('recipe_image')) {
            //画像削除
            Storage::disk('s3')->delete($recipe->recipe_image);
            $recipeImage = $request->file('recipe_image');
            $path = Storage::disk('s3')->putFile('images', $recipeImage);
            $recipe->recipe_image = $path;
        }
    
        // 材料を更新
        $ingredients = $request->input('ingredients_name');
        $quantities = $request->input('ingredients_quantity');
        $recipe->ingredients()->delete(); // 既存の材料を削除
    
        for ($i = 0; $i < count($ingredients); $i++) {
            $recipe->ingredients()->create([
                'ingredient_name' => $ingredients[$i],
                'ingredient_quantity' => $quantities[$i],
            ]);
        }
    
        // 料理工程を更新
        $processes = $request->input('cookingProcesses_process');
        $recipe->cooking_processes()->delete(); // 既存の料理工程を削除
    
        for ($i = 0; $i < count($processes); $i++) {
            $recipe->cooking_processes()->create([
                'process' => $processes[$i],
            ]);
        }
    
        // レシピを保存
        $recipe->save();
    
        //トップページへリダイレクト
        //return redirect()->route('top');
        //レシピ詳細ページへリダイレクト
        // return response()->route('to.recipe.detail', ['id' => $recipe->id]);
        // return redirect()->route('to.recipe.detail', ['id' => $recipe->id]);
    }

    public function recipeDelete($id)
    {
        //idのレシピを取得
        $recipe = Recipe::findOrFail($id);
        
        //そのidのS3を削除
        Storage::disk('s3')->delete($recipe->recipe_image);
        
        //そのidのレシピ削除
        $recipe->delete();
        
        //トップに戻る
        return Redirect::route('top');
    }
}
