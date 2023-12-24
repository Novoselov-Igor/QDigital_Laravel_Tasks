@extends('layouts.app')

@section('title')
    {{ $book->name }}
@endsection

@section('content')
    <div class="container w-75 bg-dark-subtle">
        <div class="text-center">
            <h1>{{ $book->name }}</h1>
        </div>
        <div class=" py-4 text-center">
            <span>
                {{ $book->text }}
            </span>
        </div>
    </div>
@endsection
