@extends('layouts.app')

@section('content')
    <div class="h-screen bg-[url('/img/dish.jpg')] bg-cover">
        <div class="pt-16">
            <div class="mx-auto pt-8 pb-8 w-3/4 md:w-1/2 bg-[#A5BD9F] rounded-md text-white">
                <div class="mx-auto text-center">
                    <h1 class="underline underline-offset-2 text-xl">会員登録</h1>
                </div>

                <form method="POST" action="{{ route('register') }}" class="w-1/2 mx-auto flex flex-col">
                  @csrf
                  
                  <div class="form-control mt-8">
                    <label for="name">
                      <p>ユーザー名</p>
                    </label>
                    <input type="text" name="name" class="input text-black">
                  </div>
                  
                  <div class="form-control mt-4">
                    <label for="email">
                      <p>メールアドレス</p>
                    </label>
                    <input type="email" name="email" class="input text-black">
                  </div>
                  
                  <div class="form-control mt-4">
                    <label for="password">
                      <p>パスワード</p>
                    </label>
                    <input type="password" name="password" class="input text-black">
                  </div>
                  
                  <div class="form-control mt-4">
                    <label for="password_confirmation">
                      <p>パスワード(確認用)</p>
                    </label>
                    <input type="password" name="password_confirmation" class="input text-black">
                  </div>
                  
                  <button type="submit" class="mt-8 btn bg-[#282896] hover:bg-[#3C3CAA] border-none">登録する</button>
                </form>
    
            </div>
        </div>
    </div>
@endsection