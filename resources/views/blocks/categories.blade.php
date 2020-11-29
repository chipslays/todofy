<div class="col-12 col-md-4 col-lg-3 order-2 order-md-3 d-none d-sm-block">
    <div class="container">
        <section class="mt-3">
            <div class="font-weight-bold mb-2">
                Категории
            </div>

            @if ($shared ?? false)
                @if ($is_author)
                    <div class="card border-0 w-100 mb-3">
                        <a href="{{ route('category') }}" class="m-2 button button-blue"><i class="fas fa-cog"></i> Управление</a>
                    </div>
                @endif

                <div class="card py-4 border-0 w-100">
                    @php ($cat = app('request')->input('category', false))
                    <a href="{{ route("user_notes", ['username' => $author_name]) }}" class="category-item @if ($cat == false || $cat == 'all') active-item @endif">📌 Все заметки </a>
                    @if (count($categories) > 0)
                        @foreach ($categories as $category)
                            <a href="{{ route("user_notes", ['username' => $author_name, 'category' => $category->id]) }}" class="category-item overflow-dot @if ($cat == $category->id) active-item @endif">{{ $category->emoji }} {{ $category->name }}</a>
                        @endforeach
                    @endif
                </div>
            @else 
                <div class="card border-0 w-100 mb-3">
                    <a href="{{ route('category') }}" class="m-2 button button-blue"><i class="fas fa-cog"></i> Управление</a>
                </div>

                <div class="card py-4 border-0 w-100">
                    @php ($cat = app('request')->input('category', false))
                    {{-- <a href="{{ url("/?category=all") }}" class="category-item @if ($cat == false || $cat == 'all') active-item @endif">📌 Все </a> --}}
                    <a href="{{ url("/?category=active") }}" class="category-item @if ($cat == false || $cat == 'active') active-item @endif">⚡ Активные </a>
                    <a href="{{ url("/?category=finish") }}" class="category-item @if ($cat == 'finish') active-item @endif">🏁 Завершённые</a>
                    <a href="{{ url("/?category=public") }}" class="category-item @if ($cat == 'public') active-item @endif">📢 Публичные</a>
                    <a href="{{ url("/?category=private") }}" class="category-item @if ($cat == 'private') active-item @endif">🔒 Приватные</a>
                    <a href="{{ url("/?category=archive") }}" class="category-item @if ($cat == 'archive') active-item @endif">📚 Архив</a>

                    @if (count($categories) > 0)
                        <hr class="">
                        @foreach ($categories as $category)
                            <a href="{{ url("/?category={$category->id}") }}" class="category-item overflow-dot @if ($cat == $category->id) active-item @endif">{{ $category->emoji }} {{ $category->name }}</a>
                        @endforeach
                    @endif

                </div>
            @endif
            
        </section>
    </div>
</div>