@extends('layouts.todo')

@section('title', 'Новая категория')

@section('content')
<div class="container p-2 animate__animated animate__fadeIn">
    <div class="row no-gutters">
        @include('blocks.navigation')
        
        <div class="col-12 col-md-8 col-lg-6 order-3 order-lg-2">
            <div class="container p-0">
                <section class="mt-3">
                    <div class="font-weight-bold mb-2">
                        Новая категория
                    </div>
                    <div class="card border-0 w-100 mb-3 p-2">
                        <div class="row no-gutters ">
                            <div class="col-auto mt-2 my-md-0 order-2 order-md-1">
                                <a href="#" class="emoji-picker button button-gray d-flex justify-content-center" id="emoji">📌</a>
                            </div>
                            <div class="col-12 col-md ml-md-2 order-1 order-md-2">
                                <input class="form-control h-100" type="text" id="name" value="" placeholder="Название категории">
                            </div>
                            <div class="col col-md-auto mt-2 my-md-0 ml-2 order-3 order-md-3">
                                <a href="#" class="button button-green d-flex justify-content-center" id="save" data-update="">Сохранить</a>
                            </div>
                        </div>
                    </div>

                    <section id="categories">
                        @include('blocks.list_categories')
                    </section>

                </section>
            </div>
        </div>
        
        @include('blocks.categories')
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/js/fgEmojiPicker.js') }}"></script>
@endsection

@section('script')
<script type="text/javascript">
    $(document).on('click', '#edit', function() {
        $("#save").data('update', $(this).data('id'));
        $("#emoji").html($(this).data('emoji'));
        $("#name").val($(this).data('name'));
    });

    $(document).on('click', '#delete', function() {
        swal({
            title: "Удаление",
            text: "Все заметки из этой категории будут безвозвратно удалены, вы уверены?",
            dangerMode: true,
            buttons: ['Отмена', 'Да, удалить'],
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    url: `/category/delete/` + $(this).data('id'),
                    method: 'POST',
                })
                .done(function(data) {
                if (data.ok) {
                    $("#categories").html(data.html);
                    swal("Категория успешно удалена!", {
                        icon: "success",
                    });
                } else {
                    console.log($data);
                    swal("Ой, непредвиденная ошибка!", {
                        icon: "error",
                    });
                }
                });
            }
        });
    });

    $('#save').click(function() {
        var method = 'add';
        var updateId = false;

        if ($(this).data('update') > 0) {
            updateId = $(this).data('update');
            method = 'update';
            $(this).data('update', 0);
        }

        var name = $("#name").val().trim();
        if (name !== '') {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                url: `/category/${method}`,
                method: 'POST',
                data: {emoji: $("#emoji").text(), name: $("#name").val(), updateId: updateId}
            })
            .done(function(data) {
                if (data.ok) {
                    $("#categories").html(data.html);
                } else {
                    console.log($data);
                }
            });
        }
    });

    new FgEmojiPicker({
        trigger: ['.emoji-picker'],
        position: ['bottom', 'right'],
        dir: "/assets/js/",
        emit(obj, triggerElement) {
            const emoji = obj.emoji;
            document.querySelector('.emoji-picker').innerHTML = emoji;
        }
    });
</script>
@endsection
