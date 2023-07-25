@extends('layouts.app')

{{-- この@section('content')～@endsectionまでがapp.blade.phpの@yield('content')に入る --}}
@section('content')
    @if (Auth::check())
    <div class="mx-auto pt-10 md:w-2/5 sm:w-4/5 w-full flex flex-col items-center">
        <h1 class="mb-5 underline underline-offset-2 text-xl">登録情報</h1>
        <div class="mt-2 w-2/3 flex justify-between items-center">
          <p>ユーザー名</p>
          <div class="w-2/3 flex justify-end items-center">
            <p class="w-2/5 overflow-hidden whitespace-nowrap text-right">{{ Auth::user()->name }}</p>
            <div class="w-2/5">
              <a href="{{ route('account.rename') }}" class="block ml-2 btn bg-[#282896] hover:bg-[#3C3CAA] border-none flex items-center justify-center">
                変更
              </a>
            </div>
          </div>
        </div>
        <div class="mt-2 w-2/3 flex justify-between items-center">
          <p>メールアドレス</p>
          <div class="w-2/3 flex justify-end items-center">
            <p class="w-2/5 h-6 overflow-hidden text-right">{{ Auth::user()->email }}</p>
            <div class="w-2/5">
              <a href="{{ route('email.rename') }}" class="block ml-2 btn bg-[#282896] hover:bg-[#3C3CAA] border-none flex items-center justify-center">
                変更
              </a>
            </div>
          </div>
        </div>
        <div class="mt-2 w-2/3 flex justify-between items-center">
          <p>パスワード</p>
          <div class="w-2/3 flex justify-end items-center">
            <p class="w-2/5 h-6 overflow-hidden text-right">********</p>
            <div class="w-2/5">
              <a href="{{ route('pass.rename') }}" class="block ml-2 btn bg-[#282896] hover:bg-[#3C3CAA] border-none flex items-center justify-center">
                変更
              </a>
            </div>
          </div>
        </div>
    </div>
    @else
    @endif
@endsection