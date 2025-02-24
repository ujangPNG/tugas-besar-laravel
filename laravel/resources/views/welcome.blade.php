@extends('layouts.app')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-100">
                @auth
                    <div>Hello, {{ Auth::user()->name }}</div>
                @else
                    <div>Welcome, Guest!</div>
                    <p class="mt-4">Please <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700">login</a> 
                    or <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700">register</a> to participate in auctions.</p>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection