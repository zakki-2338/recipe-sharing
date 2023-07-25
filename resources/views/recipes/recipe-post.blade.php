@extends('layouts.app')

{{-- この@section('content')～@endsectionまでがapp.blade.phpの@yield('content')に入る --}}
@section('content')
  @if (Auth::check())
    <div id="app">
      <recipe-post></recipe-post>
    </div>
  @else
  @endif
@endsection