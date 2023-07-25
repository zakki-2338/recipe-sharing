@extends('layouts.app')

{{-- この@section('content')～@endsectionまでがapp.blade.phpの@yield('content')に入る --}}
@section('content')
    {{-- ログインしているかどうかで振り分け --}}
    @if (Auth::check())
        <div class="mx-auto  pt-10 pb-20 w-11/12 md:w-2/3">
            <div class="flex items-center">
                <form method="get" action="{{ route('recipes.feed.popular')}}">
                @csrf
                    <input type="" name="recipe_feed" class="input border border-1 border-gray-300">
                    <button class="btn bg-[#282896] hover:bg-[#3C3CAA] border-none">
                        検索
                    </button>
                </form>
            </div>
            <div class="mt-2">
                @if(is_null($request) && is_null($recipe_count))
                @else
                <p class="text-lg inline-block">「{{ $request }}」のレシピ{{ $recipe_count }}件</p>
                <form method="post" action="{{ route('recipes.feed.popular.delete') }}" class="inline-block ml-2">
                    @csrf
                    @method('delete')
                        <button class="p-1 border border-1 rounded border-gray-300 hover:bg-[#AFAFAF]">この検索条件を削除</button>
                </form>
                @endif
            </div>
            
            <div>
                {{-- タブ --}}
                <div class="mt-8 w-1/2 tabs">
                    {{-- 新着順タブ --}}
                    <a href="{{ route('top') }}" class="tab tab-lifted grow bg-[#FAFAF5] border border-1 border-[#EBEBEB] text-lg">
                        新着順
                    </a>
                    {{-- 人気順タブ --}}
                    <a href="#" class="tab tab-lifted grow bg-[#FFFFFF] border border-1 border-gray-300 text-lg font-semibold">
                        人気順
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
        </div>
    @else
    @endif
@endsection