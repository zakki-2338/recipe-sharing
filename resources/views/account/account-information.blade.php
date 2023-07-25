@extends('layouts.app')

{{-- この@section('content')～@endsectionまでがapp.blade.phpの@yield('content')に入る --}}
@section('content')
    @if (Auth::check())
    <div class="mx-auto pt-10 pb-20 w-11/12 md:w-2/3">
      <div class="flex justify-between items-center p-5 border bg-[#FFFFFF] border-1 border-gray-300">
        
        <div class="flex justify-start items-center">
          <div>
            @if (is_null($user->user_image))
            <img src="{{ Storage::disk('s3')->url('other/person_icon2.png') }}" alt="ユーザーアイコンがありません" class="w-28 h-28 rounded-full object-contain border border-1 border-gray-300">
      	    @else
      	    <img src="{{ Storage::disk('s3')->url($user->user_image) }}" alt="ユーザーアイコン" class="w-28 h-28 rounded-full object-contain border border-1 border-gray-300">
      	    @endif
          </div>
          @if (Auth::user() == $user)
          <form method="post" action="{{ route('account.image.update', $user->id) }}" enctype="multipart/form-data" class="ml-2">
            @csrf
            @method('patch')
            <p>※「JPEG」のみ</p>
            <input class="h-1/6 w-28 border border-1 border-gray-300" type="file" name="new_user_image" accept="image/jpeg">
            <div class="w-24 text-center">
              <button class="btn bg-[#282896] hover:bg-[#3C3CAA] border-none">
                変更
              </button>
            </div>
          </form>
          @else
          @endif
        </div>
        
        <div class="md:flex justify-end">
          @if (Auth::user() == $user)
          <a href="{{ route('account.registration.info') }}" class="block mb-2 md:mb-0 btn bg-[#282896] hover:bg-[#3C3CAA] border-none flex items-center justify-center">
            登録情報
          </a>
          <a href="{{ route('to.delete.account.confirm') }}" class="block mt-2 md:mt-0 md:ml-2 btn bg-[#FF1E1E] hover:bg-[#FF5050] border-none flex items-center justify-center">
            退会
          </a>
          @else
          @endif
        </div>
      </div>

      <div>

        <div class="mt-8 w-2/3 tabs">
          <a href="{{ route('account.information', $user->id) }}" class="tab tab-lifted grow bg-[#FFFFFF] border border-1 border-gray-300 text-lg font-semibold">
            投稿一覧
          </a>
          <a href="{{ route('account.information.favorite', $user->id) }}" class="tab tab-lifted grow bg-[#FAFAF5] border border-1 border-[#EBEBEB] text-lg">
            お気に入り一覧
          </a>
        </div>
        
        <div>
        @if (isset($recipes))
        <ul class="bg-[#FFFFFF]">
          @foreach ($recipes as $recipe)
          
          <li class="relative z-0">
            <a href="{{ route('to.recipe.detail', $recipe->id) }}" class="flex h-40 hover:bg-[#D2D2D2]">
              <img src="{{ Storage::disk('s3')->url($recipe->recipe_image) }}" class="w-1/5 object-cover border border-1 border-gray-300" alt="料理写真">
              <div class="flex justify-between w-4/5 border border-1 border-gray-300">
                <div class="ml-1 w-11/12">
                  <p class="h-1/4 text-xl font-semibold text-[#649CAB] overflow-hidden whitespace-nowrap">{{ $recipe->recipe_name }}</p>
                  <p class="h-1/4 overflow-hidden whitespace-nowrap">by {{ $recipe->user->name }}</p>
                  <p class="h-1/2 overflow-hidden">
                    @php
                      $ingredientNames = $recipe->ingredients->pluck('ingredient_name')->toArray();
                      $joinedString = implode('、', $ingredientNames);
                      echo $joinedString;
                    @endphp
                  </p>
                </div>
              </div>
            </a>
            <div class="absolute z-10 top-0 right-2">
            @if (Auth::user() == $recipe->user)
            @else
              <div>
                @if (Auth::user()->is_favoriting($recipe->id))
                  {{-- お気に入り登録ボタン削除のフォーム --}}
                  <form method="post" action="{{ route('recipe.unfavorite', $recipe->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="hover:bg-[#D2D2D2]">
                      <i class="fa-solid fa-star"></i>
                    </button>
                  </form>
                @else
                  {{-- お気に入り登録ボタンのフォーム --}}
                  <form method="post" action="{{ route('recipe.favorite', $recipe->id) }}">
                    @csrf
                    <button class="hover:bg-[#D2D2D2]">
                      <i class="fa-regular fa-star"></i>
                    </button>
                  </form>
                @endif
              </div>
            @endif
            </div>
          </li>

          @endforeach
        </ul>
        {{-- ページネーションのリンク --}}
        {{ $recipes->links() }}
        @endif
      </div>
    </div>
    @else
    @endif
@endsection