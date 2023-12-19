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
            <form>
                <div class=" container-lg col-lg-4">
                    <div class="card">
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
                </div>
            </form>
            <form id="sendReply">
                <div class="modal fade" id="replyInput" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                     aria-labelledby="replyInput" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="d-flex w-100">
                                    <h5 class="modal-title" id="replyInput">Ответить автору:</h5>
                                    <h5 class="modal-title mx-1" id="modalHeader"></h5>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-3">
                                    <input id="titleModal" type="text"
                                           class="form-control @error('titleModal') is-invalid @enderror"
                                           name="titleModal" value="{{ old('titleModal') }}" placeholder="Заголовок"
                                           required
                                           maxlength="60">
                                    <label for="titleModal">Заголовок</label>
                                    @error('titleModal')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                <textarea class="form-control @error('textModal') is-invalid @enderror" id="textModal"
                                          name="textModal"
                                          placeholder="Сообщение" maxlength="255" rows="1" required></textarea>
                                    <label for="textModal">Сообщение</label>
                                    @error('textModal')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                                        id="sendReplyButton" onclick="sendReply(this.value)">Отправить
                                </button>
                            </div>
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
                    url: '{{ route('comments.add') }}',
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

        function sendReply(commentId) {
            var formData = {
                userId: $('meta[name="user"]').attr('content'),
                commentId: commentId,
                title: $('#titleModal').val(),
                text: $('#textModal').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                type: 'POST',
                url: '{{ route('comments.add') }}',
                data: formData,
                success: function () {
                    $('#titleModal').val('');
                    $('#textModal').val('');

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
                    var reply;
                    if (comment.comment_id === null) {
                        reply = '';
                    } else if (comment.reply !== null && comment.reply.deleted_at === null) {
                        reply = '<figcaption class="blockquote-footer">' +
                            comment.reply.text +
                            '</figcaption>';
                    } else {
                        reply = '<figcaption class="blockquote-footer">' +
                            'Комментарий удален' +
                            '</figcaption>';
                    }
                    $('#comments').append(
                        '<div class="mt-3 d-flex flex-column justify-content-center align-content-center">' +
                        '<div class="card col-lg-4 mx-auto">' +
                        '<div class="card-header bg-white d-flex justify-content-between">' +
                        '<h5>' + comment.title + '</h5>' +
                        '<h5>' + 'Автор: ' + comment.author.name + '</h5>' +
                        '</div>' +
                        '<div class="card-body">' +
                        '<p>' + reply + '</p>' +
                        '<p>' + comment.text + '</p>' +
                        '</div>' +
                        '<div id="cardFooter' + index + '" class="card-footer text-end bg-white">' +
                        '</div>' +
                        '</div>' +
                        @if(Auth::check())
                        '<button id="replyButton" onclick="replyToComment(\'' + comment.author.name + "'" + ',' + "'" + comment.id + '\')" type="button" class="btn btn-secondary col-lg-3 mx-auto mt-1">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">' +
                        '<path d="M5.921 11.9 1.353 8.62a.719.719 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z"></path>' +
                        '</svg>' +
                        '</button>' +
                        @endif
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

        function replyToComment(author, commentId) {
            var modal = new bootstrap.Modal($('#replyInput'));
            modal.show();

            $('#modalHeader').text(author);
            $('#sendReplyButton').val(commentId);
        }

    </script>
@endsection
