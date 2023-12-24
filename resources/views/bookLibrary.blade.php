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
                <div class="text-center w-100">
                    <button type="button" class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#addBook">
                        Добавить книгу
                    </button>
                </div>
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
                                    <button type="button" data-bs-dismiss="modal" class="btn btn-primary"
                                            onclick="addBook()">Добавить
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div id="change">
            <div class="modal modal-xl fade" id="changeBook" data-bs-backdrop="static" data-bs-keyboard="false"
                 tabindex="-1" aria-labelledby="changeBookLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="changeBookLabel">Изменение книги</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-floating mb-3">
                                    <input id="nameChange" type="text"
                                           class="form-control @error('nameChange') is-invalid @enderror"
                                           name="name" placeholder="Название" required maxlength="60">
                                    <label for="name">Название</label>
                                    @error('nameChange')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                                <textarea class="form-control @error('text') is-invalid @enderror"
                                                          id="textChange" name="text" placeholder="Содержание"
                                                          required style="height: 300px"></textarea>
                                    <label for="textChange">Содержание</label>
                                    @error('text')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div id="changeBookButton" class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="books" class="row">
            <!-- Здесь будут отображаться книги -->
        </div>
        <script>
            $(window).on('load', function () {
                clearBooks()
                showBooks();
            })

            function showBooks() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('book.show', ['authorId' => $authorId]) }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'authorId': {{ $authorId }}
                    },
                    success: function (books) {
                        $.each(books, function (index, book) {
                            $('#books').append(
                                '<div class="col-lg-4 col-md-6 col-sm-8 mt-3">' +
                                '<div class="card"">' +
                                '<div class="card-header bg-white text-center">' +
                                '<h4>' + book.name + '</h4>' +
                                '</div>' +
                                '<div class="card-body overflow-hidden" style="height: 200px;">' +
                                '<span>' + book.text + '</span>' +
                                '</div>' +
                                '<div class="card-body text-center">' +
                                '<a href="/library/author/{{ $authorId }}/book/' + book.id + '" class="link">Прочитать</a>' +
                                '</div>' +
                                '<div class="card-footer bg-white d-flex justify-content-between" id="bookFooter' + book.id + '">' +
                                '</div>' +
                                '</div>' +
                                '</div>');
                            if ({{ Auth::user()->id }} === book.author_id) {
                                if (!book.link_access) {
                                    var accessButton = '<button class="btn btn-secondary mx-3" onclick="giveLinkAccess(' + book.id + ')">Разрешить доступ по ссылке</button>'
                                } else {
                                    var accessButton = '<button class="btn btn-secondary mx-3" onclick="removeLinkAccess(' + book.id + ')">Запретить доступ по ссылке</button>'
                                }
                                $('#bookFooter' + book.id).append(
                                    '<button class="btn btn-primary" onclick="addChangeBookModal(' + JSON.stringify(book).replace(/"/g, '&quot;') + ')">Изменить</button>' +
                                    accessButton +
                                    '<button class="btn btn-danger" onclick="deleteBook(' + book.id + ')">Удалить</button>'
                                )
                            }
                        })
                    },
                    error: function (error) {
                        console.log('Ошибка', error)
                    }
                })
            }

            function addChangeBookModal(book) {
                var html = '<button type="button" data-bs-dismiss="modal" class="btn btn-primary"' +
                    ' onclick="changeBook(' + book.id + ')">' +
                    'Изменить' +
                    '</button>'


                var changeButton = $('#changeBookButton')
                changeButton.empty();
                changeButton.append(html);

                $('#nameChange').val(book.name);
                $('#textChange').val(book.text);

                var modal = new bootstrap.Modal($('#changeBook'));
                modal.show();
            }

            function clearBooks() {
                $('#books').empty();
            }

            function addBook() {
                var name = $('#name');
                var text = $('#text');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('book.add', ['authorId' => $authorId]) }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'name': name.val(),
                        'text': text.val(),
                        'authorId': {{ $authorId }}
                    },
                    success: function () {
                        text.val('')
                        name.val('')

                        clearBooks()
                        showBooks();
                    },
                    error: function (error) {
                        console.log('Ошибка', error)
                    }
                })
            }

            function changeBook(bookId) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('book.change', ['authorId' => $authorId]) }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'bookId': bookId,
                        'name': $('#nameChange').val(),
                        'text': $('#textChange').val(),
                    },
                    success: function () {
                        clearBooks()
                        showBooks();
                    },
                    error: function (error) {
                        console.log('Ошибка', error)
                    }
                })
            }

            function deleteBook(bookId) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('book.delete', ['authorId' => $authorId]) }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'bookId': bookId
                    },
                    success: function () {
                        clearBooks()
                        showBooks();
                    },
                    error: function (error) {
                        console.log('Ошибка', error)
                    }
                })
            }

            function giveLinkAccess(bookId) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('book.giveLinkAccess', ['authorId' => $authorId]) }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'bookId': bookId
                    },
                    success: function () {
                        clearBooks()
                        showBooks();
                    },
                    error: function (error) {
                        console.log('Ошибка', error)
                    }
                })
            }

            function removeLinkAccess(bookId) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('book.removeLinkAccess', ['authorId' => $authorId]) }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'bookId': bookId
                    },
                    success: function () {
                        clearBooks()
                        showBooks();
                    },
                    error: function (error) {
                        console.log('Ошибка', error)
                    }
                })
            }
        </script>
@endsection
