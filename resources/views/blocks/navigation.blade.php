<div class="col-12 col-lg-3 order-1 order-lg-1 d-none d-sm-block">
    <div class="container">
        <section class="mt-3">
            <div class="font-weight-bold mb-2">
                Заметки
            </div>
            <div class="card border-0 w-100">
                <a href="{{ route('new_todo') }}" class="m-2 button button-blue"><i class="fas fa-plus"></i> Новая заметка</a>
            </div>
            <div class="card py-4 mt-3 border-0 w-100">
                <a href="{{ url('/') }}" class="category-item @if (Request::path() == '' || Request::path()[0] !== '@' || Request::path()[0] !== '@' && count(app('request')->all()) == 1 && app('request')->input('category', false)) active-item @endif">Мои заметки</a>
                <a href="{{ route("user_notes", ['username' => Auth::user()->username]) }}" class="category-item @if ($is_author ?? false) active-item @endif">Мои публикации</a>
            </div>
        </section>
    </div>
</div>

{{-- <div class="col-12 col-lg-3 order-1 order-lg-1 d-block d-sm-none">
    <div class="container p-0">
        <section class="mt-3">
            <div class="card border-0 w-100">
                <a href="{{ route('new_todo') }}" class="m-2 button button-blue"><i class="fas fa-plus"></i> Новая заметка</a>
            </div>
        </section>
    </div>
</div> --}}