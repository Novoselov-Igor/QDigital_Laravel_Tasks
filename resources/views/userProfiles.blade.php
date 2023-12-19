@extends('layouts.app')

@section('title')
    Профили пользователей
@endsection

@section('content')
    <div>
        @foreach($users as $user)
            <div class="mb-3 card container-lg col-sm-6">
                <div class="card-header bg-white">Пользователь</div>
                <div class="card-body d-flex justify-content-between">
                    <div class="form-floating mb-3">
                        <h3>{{ $user->name }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('profiles.show', ['id' => $user->id]) }}"  class="btn btn-primary">Перейти</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
