<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="theme-color" content="#007bff"/>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/3a59a11235.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @yield('css')

    @yield('style')

    <title>@yield('title') - TODOfy</title>
  
</head>

<body>
    <div class="preloader ">
        <div class="vcenter text-center preloader-text">
            <div class="spinner"></div>
            <div class="text-lowercase font-weight-bold">
                Загрузка
            </div>
        </div>
    </div>

    <header>
        <nav class="navbar navbar-light bg-white navbar-todo fixed-top">
            <span class="navbar-brand font-weight-bold">
                <a href="{{ url('/') }}" class="logo">TODOfy</a>
            </span>
            <span class="navbar-text">
                @guest
                <span class="navbar-text d-none d-sm-block">
                    <a href="{{ route('login') }}" class="navbar-link">Войти</a>
                    <a href="{{ route('register') }}" class="navbar-link ml-3">Регистрация</a>
                </span>

                <a class="button button-blue px-3 menu-btn d-block d-sm-none" id="menu-toggle"><i class="fas fa-bars"></i></a>
                @else
                <div class="dropdown dropdown-menu-left">
                    <a class="button button-blue px-3 menu-btn d-block d-sm-none" id="menu-toggle"><i class="fas fa-bars"></i></a>
                    
                    <div class="d-none d-sm-block">
                        <a href="#" class="navbar-link" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">{{ "@" . Auth::user()->username }}</a>
                        <div class="dropdown-menu dropdown-menu-right bg-white" aria-labelledby="dropdownMenuButton">
                                <a class="menu-item btn-block px-1 py-0" href="{{ url('/') }}">Мои заметки</a>
                                <a class="menu-item btn-block px-1 py-0" href="{{ route("user_notes", ['username' => Auth::user()->username]) }}">Мои публикации</a>
                                <hr class="my-2">
                                <a class="menu-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"
                                >Выйти</a>
                        </div>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                @endguest
            </span>
        </nav>
    </header>

    <div class="d-flex" id="wrapper">

        <div class="bg-white border-top border-right d-block d-sm-none sidebar-fixed-scroll" id="sidebar-wrapper">
            <div class="list-group list-group-flush ">
                @guest
                    <a href="{{ route('login') }}" class="button button-blue mt-3 mx-3">Войти</a>
                    <a href="{{ route('register') }}" class="button button-blue mt-2 mx-3">Регистрация</a>
                    @if (($shared ?? false) && isset($categories) && $categories)
                    <hr class="mt-3 mb-3 w-100">
                    @endif
                @endguest

                @auth
                    <div class="font-weight-bold ml-3 mt-3 mb-2">
                        Заметки
                    </div>

                    <a href="{{ route('new_todo') }}" class="button button-blue mb-3 mx-3"><i class="fas fa-plus"></i> Новая заметка</a>

                    <div class="card border-0 w-100">
                        <a href="{{ url('/') }}" class="category-item @if (Request::path() == '' || Request::path()[0] !== '@' || Request::path()[0] !== '@' && count(app('request')->all()) == 1 && app('request')->input('category', false)) active-item @endif">Мои заметки</a>
                        <a href="{{ route("user_notes", ['username' => Auth::user()->username]) }}" class="category-item @if ($is_author ?? false) active-item @endif">Мои публикации</a>
                        <hr class="mt-2"> 
                    </div>  
                @endauth
                
                @if ($shared ?? false)
                    @if (isset($categories) && $categories)
                        <div class="font-weight-bold ml-3 mb-2">
                            Категории
                        </div>

                        <div class="card border-0 w-100">
                            @php ($cat = app('request')->input('category', false))
                            <a href="{{ route("user_notes", ['username' => $author_name]) }}" class="category-item @if ($cat == false || $cat == 'all') active-item @endif">📌 Все заметки </a>
                            @if (count($categories) > 0)
                                @foreach ($categories as $category)
                                    <a href="{{ route("user_notes", ['username' => $author_name, 'category' => $category->id]) }}" class="category-item overflow-dot @if ($cat == $category->id) active-item @endif">{{ $category->emoji }} {{ $category->name }}</a>
                                @endforeach
                            @endif
                        </div>
                    @endif
                @else 
                   @auth
                    <div class="font-weight-bold ml-3 mb-2">
                        Категории
                    </div>

                    <a href="{{ route('category') }}" class="button button-blue mb-3 mx-3"><i class="fas fa-cog"></i> Управление</a>

                    <div class="card border-0 w-100">
                        @php ($cat = app('request')->input('category', false))
                        {{-- <a href="{{ url("/?category=all") }}" class="category-item @if ($cat == false || $cat == 'all') active-item @endif">📌 Все </a> --}}
                        <a href="{{ url("/?category=active") }}" class="category-item @if ($cat == false || $cat == 'active') active-item @endif">⚡ Активные </a>
                        <a href="{{ url("/?category=finish") }}" class="category-item @if ($cat == 'finish') active-item @endif">🏁 Завершённые</a>
                        <a href="{{ url("/?category=public") }}" class="category-item @if ($cat == 'public') active-item @endif">📢 Публичные</a>
                        <a href="{{ url("/?category=private") }}" class="category-item @if ($cat == 'private') active-item @endif">🔒 Приватные</a>
                        <a href="{{ url("/?category=archive") }}" class="category-item @if ($cat == 'archive') active-item @endif">📚 Архив</a>
                        
                        @if (isset($categories) && count($categories) > 0)
                            <hr class="my-2">
                            @foreach ($categories as $category)
                                <a href="{{ url("/?category={$category->id}") }}" class="category-item overflow-dot @if ($cat == $category->id) active-item @endif">{{ $category->emoji }} {{ $category->name }}</a>
                            @endforeach
                        @endif
                    </div>

                    <hr class="my-2 w-100">
                    <div class="font-weight-bold text-primary ml-3 mt-2">{{ "@" . Auth::user()->username }}</div>
                    <a class="category-item mb-2" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();"
                    >👋 Выйти</a>
                    @endauth 
                @endif

                @auth
                @endauth

                @guest
                @endguest
            </div>
        </div>
        
        <div id="page-content-wrapper">
            @yield('content')
        </div> 

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>

    @auth
    <script async src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @endauth
    
        
    @yield('js')

    <script src="{{ asset('assets/js/app.js') }}"></script>

    @yield('script')

    <script>
        console.log("%cTODOfy ❤️", "color:#007bff; font-family: 'Ubuntu', sans-serif; font-size: 14px; font-weight: 700; padding: 25px 0;");
        $(document).ready(function() {
            $('.preloader').delay(500).fadeOut(300);
            $('[data-toggle="tooltip"]').tooltip();
            $("#menu-toggle").click(function(e) {
                $("#wrapper").toggleClass("toggled");
                if ($(this).html().includes("fa-times")) {
                    $(this).html('<i class="fas fa-bars"></i>');
                } else {
                    $(this).html('<i class="fas fa-times"></i>');
                }
            });
        });
    </script>
</body>
</html>