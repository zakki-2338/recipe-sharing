@extends('layouts.app')

@section('content')
    <div class="h-screen bg-[url('/img/dish.jpg')] bg-cover">
        <div class="pt-16">
            <div class="mx-auto pt-8 pb-8 w-3/4 md:w-1/2 bg-[#A5BD9F] rounded-md text-white">
                <div class="mx-auto text-center">
                    <h1 class="underline underline-offset-2 text-xl">ログイン</h1>
                </div>
            
                <form method="POST" action="{{ route('login') }}" class="w-1/2 mx-auto flex flex-col">
                    @csrf
        
                    <div class="form-control mt-4">
                        <label for="email">
                            <p>Email</p>
                        </label>
                        <input type="email" name="email" class="input text-black">
                    </div>
        
                    <div class="form-control mt-4">
                        <label for="password">
                            <p>Password</p>
                        </label>
                        <input type="password" name="password" class="input text-black">
                    </div>
        
                    <button type="submit" class="mt-8 btn bg-[#282896] hover:bg-[#3C3CAA] border-none">ログイン</button>
                </form>
                    
            </div>
        </div>
    </div>
@endsection