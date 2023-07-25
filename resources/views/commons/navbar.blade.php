<header>
    <nav class="navbar bg-[#A5BD9F] text-white flex justify-between">
        {{-- トップページへのリンク --}}
        <div>
            <h1><a class="block flex items-center p-0 btn btn-ghost whitespace-nowrap" href="/">レシピ共有サイト</a></h1>
        </div>
        
        <div class="list-none flex justify-end">
            {{--ログインしているかどうかで条件分岐--}}
            @if (Auth::check())
                {{-- レシピ投稿ページへのリンク --}}
                <li class="w-24 mr-2"><a class="block flex items-center p-0 btn btn-ghost whitespace-nowrap" href="{{ route('to.recipe.post') }}">レシピ投稿</a></li>
                {{-- ユーザ情報ページへのリンク --}}
                {{-- Auth::user()でログイン中のユーザー名を取得 --}}
                <li class="w-24 mr-2"><a class="block flex items-center p-0 btn btn-ghost normal-case whitespace-nowrap overflow-hidden" href="{{ route('account.information', Auth::user()->id) }}">{{ Auth::user()->name }}</a></li>
                {{-- ログアウトへのリンク --}}
                <li class="w-24 mr-2">
                    <form method="POST" action="{{ route('logout') }}" >
                        @csrf
                        <button class="block flex items-center p-0 w-full btn btn-ghost whitespace-nowrap">ログアウト</button>
                    </form>
                </li>
            @else
            @endif
        </div>
    </nav>
</header>