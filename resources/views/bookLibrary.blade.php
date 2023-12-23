@extends('layouts.app')

@section('title')
    Библиотека книг
@endsection

@section('content')
    <div class="container">
        <div class="text-center">
            <h1>Библиотека книг</h1>
        </div>
        @if($authorId === '' . Auth::user()->id)
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBook">
                    Добавить книгу
                </button>
                <div class="modal modal-xl fade" id="addBook" data-bs-backdrop="static" data-bs-keyboard="false"
                     tabindex="-1"
                     aria-labelledby="addBookLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="addBookLabel">Добавление книги</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="form-floating mb-3">
                                        <input id="name" type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name') }}" placeholder="Название" required
                                               maxlength="60">
                                        <label for="name">Название</label>
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-floating mb-3">
                                    <textarea class="form-control @error('text') is-invalid @enderror" id="text"
                                              name="text"
                                              placeholder="Содержание"
                                              required
                                              style="height: 300px"></textarea>
                                        <label for="text">Содержание</label>
                                        @error('text')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" data-bs-dismiss="modal" class="btn btn-primary" onclick="addBook()">Добавить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <script>
            function addBook() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('book.add', ['authorId' => $authorId]) }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'name': $('#name').val(),
                        'text': $('#text').val(),
                        'authorId': {{ $authorId }}
                    },
                    success: function (data) {
                        console.log(data)
                    },
                    error: function (error) {
                        console.log('Ошибка', error)
                    }
                })
            }
        </script>
@endsection
