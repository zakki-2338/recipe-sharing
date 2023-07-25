<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    //投稿にコメントするアクション
    public function recipeComment(Request $request, $id)
    {
        $request->validate([
            'new_comment' => ['required', 'string', 'max:255'],
        ]);
        
        $recipe = Recipe::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）が、 idの投稿をお気に入り登録する
        // Userモデルからcommentメソッドを持ってくる
        Auth::user()->comments()->attach($recipe, [
            'comment' => $request->new_comment
        ]);
        
        // 前のURLへリダイレクトさせる
        return back();
    }
    
    //コメントを削除するアクション
    public function recipeCommentDelete($id)
    {
        // 対象のコメントを見つける。
        $comment = Comment::find($id);
        
        //コメントを削除
        $comment->delete();
        
        // 前のURLへリダイレクトさせる
        return back();
    }
}
