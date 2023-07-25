<!DOCTYPE html>
<html lang="ja" class="bg-[#FAFAF5]">
    <head>
        <meta charset="utf-8">
        <title>recipe_sharing</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        @vite('resources/css/app.css')
        {{-- jquery使用のため追記 --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
     <body class="bg-[#FAFAF5]">

        {{-- ナビゲーションバー --}}
        @include('commons.navbar')

        <div>
            {{-- エラーメッセージ --}}
            @include('commons.error_messages')

            @yield('content')
        </div>
    </body>
</html>