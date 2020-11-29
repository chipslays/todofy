@extends('layouts.todo')

@section('title', "Заметки @{$author_name}")

@section('content')
<div class="container p-2 animate__animated animate__fadeIn">
    <div class="row no-gutters">
        @auth
            @include('blocks.navigation')
        @endauth
        
        @include('blocks.list')       
        
        
        @include('blocks.categories')
        
    </div>
</div>
@endsection

@if ($is_author ?? false)
    @include('blocks.script-note-manage')
@endif