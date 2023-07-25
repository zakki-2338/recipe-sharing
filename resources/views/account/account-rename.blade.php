@extends('layouts.app')

@section('content')
    @if (Auth::check())
    <form method="post" action="{{ route('account.name.update') }}">
      @csrf
      @method('patch')
      
        <div class="mt-10 w-1/2 mx-auto flex flex-col items-center">
          
          <h1 class="underline underline-offset-2 text-xl">ユーザー名変更</h1>
          
          <div>
            <div class="mt-8">          
              <label for="current_name" class="label">
                <p class="label-text">現在</p>
              </label>
              <p>{{ Auth::user()->name }}</p>
            </div>
            
            <div class="mt-8">
              <label for="new_name" class="label">
                <p class="label-text">変更後</p>
              </label>
              {{--                                                                            入力必須 自動でカーソルを当てる 入力候補を提示して入力内容を自動補完する--}}
              <input type="text" name="new_name" class="input border border-1 border-gray-300" required autofocus autocomplete="name" />
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