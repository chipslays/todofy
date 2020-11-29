@extends('layouts.todo')

@section('title', 'Мои заметки')

@section('content')
<div class="container p-2 animate__animated animate__fadeIn">
    <div class="row no-gutters">
        @include('blocks.navigation')
        @include('blocks.list')
        @include('blocks.categories')
    </div>
</div>
@endsection

@include('blocks.script-note-manage')