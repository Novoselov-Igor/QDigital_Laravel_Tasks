@extends('layouts.app')

@section('title')
    Домашняя страница
@endsection

@section('content')
    <div>
        <form>
            <div class="card container-lg col-sm-4">
                <div class="card-header bg-white">Комментарий</div>
                <div class="card-body">
                    <div class="form-floating mb-3">
                        <input id="title" type="text" class="form-control @error('title') is-invalid @enderror"
                               name="title" value="{{ old('title') }}" placeholder="Заголовок" required>
                        <label for="title">Заголовок</label>
                        @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                    <textarea class="form-control @error('text') is-invalid @enderror" id="text" name="text"
                              placeholder="Сообщение" maxlength="255" rows="1"></textarea>
                        <label for="text">Сообщение</label>
                        @error('text')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end mx-3">
                        <button type="submit" class="btn btn-primary">
                            Отправить
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
