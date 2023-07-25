@extends('layouts.app')

{{-- この@section('content')～@endsectionまでがapp.blade.phpの@yield('content')に入る --}}
@section('content')
  @if (Auth::user()->id == $recipeData->user->id)
    <div id="app">
      <recipe-edit
       :recipe-data="{{ $recipeData }}"
       :ingredients-data="{{ $ingredientsData }}"
       :cooking-processes-data="{{ $cookingProcessesData }}"
      ></recipe-edit>
    </div>
  @else
  @endif
@endsection