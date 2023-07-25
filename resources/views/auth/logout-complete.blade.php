@extends('layouts.app')

{{-- この@section('content')～@endsectionまでがapp.blade.phpの@yield('content')に入る --}}
@section('content')
    <div class="h-screen bg-[url('/img/dish.jpg')] bg-cover">
        <div class="pt-16">
          <div class="flex flex-col items-center mx-auto pt-8 pb-8 w-64 lg:w-80 bg-[#A5BD9F] rounded-md text-white">
            <div class="mx-auto">
              <h1 class="underline underline-offset-2 text-center text-xl">ログアウト</h1>
              <p class="mt-8">ログアウト致しました。<br>
                 ご利用ありがとうございました。
              </p>
            </div>
            <a href="/" class="mt-12 btn bg-[#282896] hover:bg-[#3C3CAA] border-none">
              トップへ
            </a>
          </div>
        </div>
    </div>
@endsection