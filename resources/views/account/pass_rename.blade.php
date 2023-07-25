@extends('layouts.app')

@section('content')
    @if (Auth::check())
    <form method="post" action="{{ route('pass.update') }}">
      @csrf
      @method('patch')
      
        <div class="mt-10 w-1/2 mx-auto flex flex-col items-center">
          
          <h1 class="underline underline-offset-2 text-xl">パスワード変更</h1>
          
          <div>
            <div class="mt-8">          
              <label for="current_password" class="label">
                <p class="label-text">現在</p>
              </label>
              <input type="password" name="current_password" class="input border border-1 border-gray-300" >
            </div>
            <!-- current_passwordが間違っていた時に示すフラッシュメッセージ -->
            @if (session('flash_message'))
            <div class="flash_message">
                {{ session('flash_message') }}
            </div>
            @endif
            
            <div class="mt-8">
              <label for="new_password" class="label">
                <p class="label-text">変更後</p>
              </label>
              <input type="password" name="new_password" class="input border border-1 border-gray-300">
            </div>
            
            <div class="mt-8">
              <label for="new_password_confirmation" class="label">
                <p class="label-text">変更後(確認用)</p>
              </label>
              <input type="password" name="new_password_confirmation" class="input border border-1 border-gray-300" >
            </div>
          </div>
          
          <button class="mt-12 btn bg-[#282896] hover:bg-[#3C3CAA] border-none">
            変更
          </button>
        </div>
    </form>
    
    
    
    @else
    @endif
@endsection