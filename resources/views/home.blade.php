@extends('layouts.app')

@section('title')
    Профиль
@endsection

@section('content')
    <div>
        <div class="text-center mb-2">
            @if(isset($user))
                <meta name="user" content="{{ $user->id }}">
                <h2>{{ $user->name }}</h2>
            @else
                <meta name="user" content="{{ Auth::user()->id }}">
                <h2>{{ Auth::user()->name }}</h2>
            @endif
        </div>
        @if(Auth::check())
            <form method="post">
                <div class="card container-lg col-sm-4">
                    <div class="card-header bg-white">Написать комментарий</div>
                    <div class="card-body">
                        <div class="form-floating mb-3">
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror"
                                   name="title" value="{{ old('title') }}" placeholder="Заголовок" required
                                   maxlength="60">
                            <label for="title">Заголовок</label>
                            @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                    <textarea class="form-control @error('text') is-invalid @enderror" id="text" name="text"
                              placeholder="Сообщение" maxlength="255" rows="1" required></textarea>
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
        @endif
        <div id="comments">
            <!-- Здесь будут отображаться комментарии -->
        </div>
    </div>

    <script>
        $(window).on('load', function () {
            clearComments();
            showComments();
        })

        $(document).ready(function () {
            $('form').submit(function (event) {
                event.preventDefault();

                var formData = {
                    userId: $('meta[name="user"]').attr('content'),
                    title: $('#title').val(),
                    text: $('#text').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    type: 'POST',
                    url: '{{ route('comments.addNew') }}',
                    data: formData,
                    success: function () {
                        $('#title').val('');
                        $('#text').val('');

                        clearComments();
                        showComments();
                    },
                    error: function (error) {
                        console.log("Ошибка:", error);
                    }
                });
            });
        });

        function clearComments() {
            $('#comments').empty();
        }

        function showComments() {
            clearComments()
            $.ajax({
                type: 'GET',
                url: '{{ route('comments.show') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'userId': $('meta[name="user"]').attr('content'),
                    'type': 'compact'
                },
                success: function (data) {
                    showComment(data.comments)
                    if (data.size === 'large') {
                        $('#comments').append(
                            '<div class="mt-3 d-flex justify-content-center">' +
                            '<button class="btn border border-2" onclick="showAllComments()" id="showAllComments">' +
                            'Показать все комментарии' +
                            '</button>' +
                            '</div>'
                        )
                    }
                },
                error: function (error) {
                    console.log('Ошибка', error)
                }
            })
        }

        function deleteComment(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('comments.delete') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'commentId': id
                },
                success: function () {
                    showComments()
                },
                error: function (error) {
                    console.log('Ошибка', error)
                }
            })
        }

        function showAllComments() {
            $.ajax({
                type: 'GET',
                url: '{{ route('comments.show') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'type': 'all'
                },
                success: function (data) {
                    showComment(data.comments)
                    $('#showAllComments').remove()
                    $('#comments').append(
                        '<div class="mt-3 d-flex justify-content-center">' +
                        '<button class="btn border border-2" onclick="showComments()" id="showAllComments">' +
                        'Скрыть' +
                        '</button>' +
                        '</div>'
                    )
                },
                error: function (error) {
                    console.log('Ошибка', error)
                }
            })
        }

        function showComment(comments) {
            $.each(comments, function (index, comment) {
                    $('#comments').append(
                        '<div class="card container-lg col-sm-4 mt-3">' +
                        '<div class="card-header bg-white d-flex justify-content-between">' +
                        '<h5>' + comment.title + '</h5>' +
                        '<h5>' + 'Автор: ' + comment.author.name + '</h5>' +
                        '</div>' +
                        '<div class="card-body">' +
                        '<p>' + comment.text + '</p>' +
                        '</div>' +
                        '<div id="cardFooter' + index + '" class="card-footer text-end bg-white">' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );
                    @if(Auth::check())
                    if (comment.author_id === {{ Auth::user()->id }} || '{{ Auth::user()->id }}' === $('meta[name="user"]').attr('content')) {
                        $('#cardFooter' + index).append(
                            '<button id="' + comment.id + '" class="btn btn-danger" onclick="deleteComment(this.id)">Удалить</button>'
                        );
                    }
                    @endif
                }
            )
        }

    </script>
@endsection
