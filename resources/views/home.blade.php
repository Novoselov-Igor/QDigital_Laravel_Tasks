@extends('layouts.app')

@section('title')
    Домашняя страница
@endsection

@section('content')
    <div>
        <form method="post">
            <div class="card container-lg col-sm-4">
                <div class="card-header bg-white">Комментарий</div>
                <div class="card-body">
                    <div class="form-floating mb-3">
                        <input id="title" type="text" class="form-control @error('title') is-invalid @enderror"
                               name="title" value="{{ old('title') }}" placeholder="Заголовок" required maxlength="60">
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
                    userId: {{ Auth::user()->id }},
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
            console.log(comments)
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
                        '</div>' +
                        '</div>'
                    );
                }
            )
        }
    </script>
@endsection
