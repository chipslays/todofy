@extends('layouts.todo')

@section('title', 'Главная')

@section('content')
<div class="container-fluid">
    <section class="vertical-75 mt-5 mt-lg-0">
        <div class="row">
            <div class="col order-2 order-lg-1 my-auto animate__animated animate__fadeInLeft">
                <h1 class="font-weight-bold">TODOfy</h1>
                <h5 class="text-muted font-14">
                    Простой, но мощный инструмент для работы с задачами.
                </h5>
                <a href="/register" class="btn btn-primary mt-3 d-block d-md-inline-block animate__animated animate__fadeIn animate__delay-1s">Попробовать</a>
            </div>
            <div class="col-12 order-1 order-lg-2 col-lg-auto mb-5 mb-lg-0 animate__animated animate__fadeInRight">
                <img class="image" src="assets/images/study-1.png" alt="study-girl">
            </div>
        </div>
    </section>
</div>
@endsection

@include('blocks.footer')
