@extends('layouts.todo')

@section('title')
{{ $title }}
@endsection

@section('content')
<div class="container p-2 animate__animated animate__fadeIn">
    <div class="card border-0 w-100 h-100 px-3 py-5 text-muted text-center">
        <h1 class="mb-3">{{ $icon }}</h1>
        <span>{{ $text }}</span>
        <span class="mt-4">
            <a href="{{ $link }}" class=" button button-blue w-5">{{ $btn }}</a>
        </span>

    </div>
</div>
@endsection
