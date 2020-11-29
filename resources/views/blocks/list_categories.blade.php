@if (count($categories) == 0)
    <div class="card border-0 w-100 h-100 px-3 py-5 text-muted text-center">
        <h1 class="mb-3">😯</h1>
        <span>Кажется, здесь нет доступных категорий.</span>
    </div>
@else 
    @foreach ($categories as $category)
    <div class="card border-0 w-100 mb-3 p-3">
        <div class="row no-gutters ">
            <div class="col-10 font-weight-bold overflow-dot">
                {{ $category->emoji }} {{ $category->name }}
            </div>
            <div class="col-1 text-center text-muted">
                <i class="fas fa-edit list-icon" data-id="{{ $category->id }}" data-emoji="{{ $category->emoji }}" data-name="{{ $category->name }}" id="edit"></i>
            </div>
            <div class="col-1 text-center">
                <i class="fas fa-trash list-icon text-danger" data-id="{{ $category->id }}" id="delete"></i>
            </div>
        </div>
    </div>
    @endforeach
@endif

