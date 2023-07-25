@extends('layouts.app')

{{-- この@section('content')～@endsectionまでがapp.blade.phpの@yield('content')に入る --}}
@section('content')
    @if (Auth::check())
    <div class="mt-10 mx-auto mb-10 w-11/12 md:w-2/3 bg-[#FFFFFF] outline outline-2 outline-gray-300">
      <div class="mx-auto pt-10 pb-20 mb-10 w-4/5 ">
        <div class="pb-10 flex justify-between items-center">
          <div class="w-1/2 flex justify-start items-center">
            <p>by</p>
            <a href="{{ route('account.information', $recipe->user->id) }}" class="ml-1 flex items-center w-20 btn btn-ghost normal-case text-lg font-semibold whitespace-nowrap overflow-hidden">{{ $recipe->user->name }}</a>
          </div>
          @if (Auth::user() == $recipe->user)
          {{-- Auth::user()->id==$post->user->id --}}
          <div class="md:flex">
            
            <a href="{{ route('to.recipe.edit', $recipe->id) }}" class="block mb-2 md:mb-0 w-20 flex items-center btn normal-case bg-[#282896] hover:bg-[#3C3CAA] border-none">
              編集
            </a>
            
            <form method="post" action="{{ route('recipe.delete', $recipe->id) }}" class="w-1/4">
            @csrf
            @method('delete')
              <button class="block mt-2 md:mt-0 md:ml-2 w-20 btn bg-[#FF1E1E] hover:bg-[#FF5050] border-none" onclick='return confirm("本当に削除しますか？")';>
                削除
              </button>
            </form>
          </div>
          @else
          @endif
        </div>
        
        <div class="pt-10">
          <p class="text-2xl font-semibold text-[#649CAB]">{{ $recipe->recipe_name }}</p>
        </div>
        
        <div class="mt-6 md:flex md:justify-between">
          <div class="md:w-1/2">
            <p class="text-lg font-semibold">料理写真</p>
            <img src="{{ Storage::disk('s3')->url($recipe->recipe_image) }}" class="block mx-auto md:ml-0 md:mr-auto w-11/12 h-auto outline outline-1 outline-gray-300" alt="料理写真">
          </div>

          <div class="mt-6 md:mt-0 md:w-1/2">
              <p class="text-lg font-semibold">材料</p>
              @foreach ($recipe->ingredients as $ingredient)
                <div class="flex justify-between border-t-2 border-gray-300 last:border-b-2 last:border-gray-300">
                  <p class="overflow-x-auto overflow-y-auto">{{ $ingredient->ingredient_name }}</p>
                  <p class="overflow-x-auto overflow-y-auto">{{ $ingredient->ingredient_quantity }}</p>
                </div>
              @endforeach
          </div>
        </div>
  
        <div class="mt-6">
          <p class="text-lg font-semibold">料理工程</p>
          @foreach ($recipe->cooking_processes as $index => $process)
            <p class="border-t-2 border-gray-300 last:border-b-2 last:border-gray-300">{{ $index + 1 }}. {{ $process->process }}</p>
          @endforeach
        </div>
        
        <div class="pt-16">
          <form method="post" action="{{ route('recipe.comment', $recipe->id) }}">
          @csrf
            <div>
              <input class="w-full outline outline-2 outline-gray-500" name="new_comment">
            </div>
            <div class="mt-5 text-center">
              <button class="btn bg-[#282896] hover:bg-[#3C3CAA] border-none">
                コメントする
              </button>
            </div>
          </form>
        </div>
        
        <div class="pt-6">
          <p class=" text-lg font-semibold">コメント</p>
        @if (isset($comments))
          <ul>
              @foreach ($comments as $comment)
                <div class="w-full outline outline-1 outline-gray-300">
                  <div>
                    <div>
                      <p>{{ $comment->user->name }} より:</p>
                      {{-- {{ $comment->user->name }} --}}
                      <p class="text-[#B4B4B4]">{{ $comment->created_at }}</p>
                    </div>
                    <p>{{ $comment->comment }}</p>
                  </div>
                  @if (Auth::user() == $comment->user)
                  <form method="post" action="{{ route('recipe.comment.delete', $comment->id) }}">
                  @csrf
                  @method('DELETE')
                    <button class="btn bg-[#FF1E1E] hover:bg-[#FF5050] border-none">
                      コメントを削除
                    </button>
                  </form>
                  @else
                  @endif
                </div>
              @endforeach
         </ul>
          {{-- ページネーションのリンク --}}
          {{ $comments->links() }}
        @endif
        </div>
    
      </div>
    </div>
    @else
    @endif
@endsection