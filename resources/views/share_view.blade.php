@extends('layouts.todo')

@section('title', "{$item->name} | @{$author_name}")

@section('content')
<div class="container p-2 animate__animated animate__fadeIn">
    <div class="row no-gutters">

        @auth
            @include('blocks.navigation')
        @endauth
        
        <div class="col-12 col-md-8 col-lg-6 order-3 order-lg-2 mx-auto">
            <div class="container p-0">
                <section class="my-3">
                    <div class="font-weight-bold mb-2">
                        Заметка <a href="{{ route("user_notes", ['username' => $author_name]) }}" class="text-primary text-decoration-none">{{ '@' . $author_name }}</a>
                    </div>

                    <div class="" id="items">
                        <div class="card border-0 mb-3 w-100">
                            <div class="card-body">
                                <div class="overflow-dot">
                                    <div class="list-item-header align-middle">
                                        {{ $item->name }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            @include('blocks.editorjs_view')
                        </div>

                        <div class="text-muted small row no-gutters">
                            <div class="col-12 col-md">
                                Просмотров: {{ $item->views }}
                            </div>
                            <div class="col-12 col-md-auto mr-auto">
                                Последнее обновление {{ date('d.m.Y в H:i', strtotime($item->updated_at)) . ' (UTC ' . date('P') . ')' }}
                            </div>
                        </div> 
                    </div>
                    
                </section>
            </div>
        </div>
        
        @auth
            @include('blocks.categories')
        @endauth

    </div>
</div>
@endsection
