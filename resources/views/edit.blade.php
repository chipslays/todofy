@extends('layouts.todo')

@section('title', 'Редактирование заметки')

@section('content')
<div class="container p-2 animate__animated animate__fadeIn">
    <div class="row no-gutters">
        @include('blocks.navigation')
        
        <div class="col-12 col-md-8 col-lg-6 order-3 order-lg-2">
            <div class="container p-0">
                <section class="mt-3">
                    <div class="font-weight-bold mb-2">
                        Редактирование заметки
                    </div>
                    <div class="card border-0 w-100 mb-3 p-2">
                        <div class="row no-gutters">
                            <div class="col-12 col-md-3 mt-2 mt-md-0 order-2 order-md-1">
                                <select class="form-control h-100" id="category">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @if ($category->id == $item['category_id']) selected @endif>{{ $category->emoji }} {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md ml-0 ml-md-2 order-1 order-md-2">
                                <input class="form-control h-100" type="text" id="name" value="{{ $item['name'] }}" placeholder="Название заметки">
                            </div>
                            <div class="col-12 col-md-auto mt-2 mt-md-0 ml-0 ml-md-2 order-3 order-md-3">
                                <a href="#" class="button button-green d-flex justify-content-center" id="save">Сохранить</a>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 mb-3 w-100">
                        <div class="card-body">
                            <div id="editorjs" class=""></div>
                        </div>
                    </div>

                </section>
            </div>
        </div>
        
        @include('blocks.categories')
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.19.0/dist/editor.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@1.2.2/dist/bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/underline@1.0.0/dist/bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@2.6.0/dist/bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@2.8.0/dist/bundle.min.js"></script>
@endsection

@section('script')
<script type="text/javascript">
    const editor = new EditorJS({
        holder: 'editorjs',
        tools: {
            list: {
                class: List,
                inlineToolbar: true,
            },
            Marker: {
                class: Marker,
            },
            paragraph: {
                class: Paragraph,
                inlineToolbar: true,
            },
            underline: Underline,
            header: Header,
        },
        data: {!! $item['data'] !!}
    });

    $("#save").click(function () {
        editor.save().then((output) => {
            var name = $("#name").val().trim();
            if (name !== '') {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    url: `/edit/{{ $item['id'] }}`,
                    method: 'POST',
                    data: {name: name, categoryId: $('#category option:selected').val(), data: JSON.stringify(output)}
                })
                .done(function(data) {
                    if (data.ok) {
                        window.location.href = '{{ url()->previous() }}';
                    } else {
                        console.log(data);
                    }
                });
            }
        }).catch((error) => {
            console.log('Saving failed: ', error)
        });
    });
</script>
@endsection
