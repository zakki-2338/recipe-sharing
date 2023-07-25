@extends('layouts.app')

{{-- この@section('content')～@endsectionまでがapp.blade.phpの@yield('content')に入る --}}
@section('content')
    @if (Auth::check())
    <div class="mt-10 w-1/2 mx-auto flex flex-col items-center">
            <img src="{{ Storage::disk('s3')->url('other/caution_mark2.png') }}" alt="caution_mark2" class="object-contain w-24 h-20">
        <p class="mt-5 underline underline-offset-2 text-xl">退会手続き</p>
        <p class="mt-8">退会に伴い、投稿したレシピ等ユーザーに関する情報はすべて削除されますがよろしいでしょうか。
        </p>
        
        <form method="post" action="{{ route('delete.account') }}" class="p-6">
            @csrf
            @method('delete')
            <button href="#" class="mt-12 btn bg-[#FF1E1E] hover:bg-[#FF5050] border-none">
                退会
            </button>
        </form>
        
    </div>
    @else
    @endif
@endsection