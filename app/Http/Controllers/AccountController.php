<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    //ユーザー情報ページ表示
    public function toAccountInformation($id)
    {
        $data = [];
        
        //idの値で投稿を検索して取得
        $user = User::findOrFail($id);
        
        // ユーザの投稿の一覧を作成日時の降順で取得
        $recipes = $user->recipes()->orderBy('created_at', 'desc')->paginate(5);
        $data = [
            'user' => $user,
            'recipes' => $recipes,
        ];
        
        return view('account.account-information', $data);
    }

    //ユーザー情報お気に入りページ表示
    public function toAccountInformationFavorite($id)
    {
        $data = [];
        
        //idの値で投稿を検索して取得
        $user = User::findOrFail($id);
        
        //Userモデルで作成したfeed_recipes()メソッドを使用
        //ユーザのお気に入り一覧を作成日時の降順で取得
        $favorite_recipes = $user->feed_recipes()->orderBy('created_at', 'desc')->paginate(5);
        
        $data = [
            'user' => $user,
            'recipes' => $favorite_recipes,
        ];
        
        return view('account.account-information-favorite', $data);
    }

    //ユーザーアイコン変更
    public function accountImageUpdate(Request $request, $id)
    {
        //バリデーション
        $request->validate([
            'new_user_image' => 'required|image',
        ]);

        //idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

	    //user_imageがnullじゃない場合は画像削除
	    if (is_null($user->user_image)) {
	    } else {
	        Storage::disk('s3')->delete($user->user_image);
	    }
	    
	    //アップデートする画像を変数に代入
        $new_user_image = $request->file('new_user_image');
        
        //S3のimagesフォルダに、第二引数に指定した画像を保存する
        $path = Storage::disk('s3')->putFile('userImage', $new_user_image);
        
        $user->user_image = $path;

        //現在のユーザー情報を更新
        $user->save();

        //ユーザーページへ戻る
        return Redirect::route('account.information', $user->id);
    }

    //ユーザー名変更ページ表示
    public function toAccountRename(Request $request)
    {
        return view('account.account-rename', [
            $user = Auth::user()
        ]);
    }

    //ユーザー名変更
    public function accountNameUpdate(Request $request)
    {
        $request->validate([
            'new_name' => ['required', 'string', 'max:255'],
        ]);

        //現在のユーザー情報を取得
        $user = Auth::user();

        //現在のユーザー情報中のnameに変更後のnew_nameの中に入っている値を代入
        $user->name = $request->new_name;

        //現在のユーザー情報中の名前情報を更新
        $user->save();

        //登録情報ページへ戻る
        return Redirect::route('account.registration.info');
    }

    //メールアドレス変更ページ表示
    public function toEmailRename(Request $request)
    {
        return view('account.email-rename', [
            $user = Auth::user()
        ]);
    }

    //メールアドレス変更
    public function emailUpdate(Request $request)
    {
        $request->validate([
            'new_email' => ['required', 'string', 'email', 'max:255'],
        ]);

        //現在のユーザー情報を取得
        $user = Auth::user();
        
        //現在のユーザー情報中のemailに変更後のnew_emailの中に入っている値を代入
        $user->email = $request->new_email;
        
        //現在のユーザー情報中のemail情報を更新
        $user->save();

        //登録情報ページへ戻る
        return Redirect::route('account.registration.info');
    }

    //パスワード変更ページ表示
    public function toPassRename(Request $request)
    {
        return view('account.pass_rename', [
            $user = Auth::user()
        ]);
    }

    //パスワード変更
    public function passUpdate(Request $request)
    {
        //バリデーション
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        //current_passwordが現在のユーザーパスワードと合っているかを確認、合っている場合はifの中の処理を実行
        if(Hash::check($request->current_password, auth()->user()->password)){
            //パスワードの更新
            $user = Auth::user();

            //現在のユーザー情報中のpasswordに、変更後のnew_passwordの中に入っている値をハッシュして代入
            $user->password = Hash::make($request->new_password);

            //現在のユーザー情報中のpassword情報を更新
            $user->save();

            //登録情報ページへ戻る
            return Redirect::route('account.registration.info');
        } else {
            return back()->with("flash_message", "current_password Doesn't match!");
        }
    }
}
