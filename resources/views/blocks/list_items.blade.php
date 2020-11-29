@if (count($data) == 0)
    <div class="card border-0 w-100 h-100 px-3 py-5 text-muted text-center">
        @if ($is_author ?? true)
            <h1 class="mb-3">📌</h1>
            <span>Заметок нет, <a href="{{ route('new_todo') }}" class="text-decoration-none">добавить</a> новую?</span>
        @else 
            <h1 class="mb-3">🙄</h1>
            <span>Кажется, <a href="{{ route("user_notes", ['username' => $author_name]) }}" class="text-primary text-decoration-none font-weight-bold">{{ '@' . $author_name }}</a> ещё не поделился ни одной заметкой.</span>
        @endif
        
    </div>
@else
    @php ($cat = app('request')->input('category', false))
    @foreach ($data as $item)
        @php ($collapseId = 'COLLAPSE_' . md5($item->name . $item->created_at . rand()))
        <div class="list-item card border-0 @if (!$loop->first) mt-3 @endif w-100" data-toggle="collapse" href="#{{ $collapseId }}"
            role="button" aria-expanded="false" aria-controls="{{ $collapseId }}">
            <div class="card-body">
                <div class="row no-gutters">
                    @if (($is_author ?? true))
                        @php ($ml_3 = 'ml-3')
                        <div class="col-auto text-center">
                            @if ($item->pinned == \App\Models\Todo::PINNED && ($shared ?? false)) 
                                <i class="fas fa-thumbtack text-primary" data-toggle="tooltip" title="Закреплённая заметка"></i>
                            @elseif ($item->shared == \App\Models\Todo::SHARE_LINK)
                                <i class="fas fa-bullhorn text-primary" data-toggle="tooltip" title="Публичная заметка"></i>
                            @else 
                                <i class="fas fa-lock text-primary" data-toggle="tooltip" title="Приватная заметка"></i>
                            @endif
                        </div>   
                    @elseif ($item->pinned == \App\Models\Todo::PINNED) 
                        <div class="col-auto text-center mr-3">
                            <i class="fas fa-thumbtack text-primary" data-toggle="tooltip" title="Закреплённая заметка"></i>
                        </div>  
                    @endif
                    
                    <div class="col {{ $ml_3 ?? '' }} overflow-dot">
                        <div class="list-item-header align-middle">
                            {{ $item->name }}
                        </div>
                    </div>
                    <div class="col-auto ml-4">
                        <div class="float-right">
                            <i class="arrow-down down text-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="collapse @if ($item->collapsed == \App\Models\Todo::COLLAPSED && ($shared ?? false)) show @endif" id="{{ $collapseId }}">
            
            @if ($is_author ?? true)
            <div class="card card-body border-0 mt-2">
                <div class="row no-gutters ">
                    @if ($cat == 'archive' || $cat == 'finish')
                    <div class="col">
                        <a href="#" class="button button-green btn-block overflow-dot" id="manage" data-method="active" data-id="{{ $item->id }}"><i
                                class="fas fa-check"></i> Активировать</a>
                    </div>
                    @endif

                    @if ($cat !== 'archive' && $cat !== 'finish')
                    <div class="col">
                        <a href="#" class="button button-green btn-block" id="manage" data-method="finish" data-id="{{ $item->id }}" data-toggle="tooltip" title="Завершить"><i
                                class="fas fa-check"></i></a>
                    </div>
                    <div class="col ml-3">
                        <a href="{{ url("/edit/{$item->id}") }}" class="button button-blue btn-block" id="#edit" data-toggle="tooltip" title="Редактировать"><i
                                class="fas fa-edit"></i></a>
                    </div>
                    @endif
                    
                    <div class="col ml-3">
                        <a href="#" class="button button-red btn-block overflow-dot" id="manage" data-method="@if ($cat == 'archive') delete @else archive @endif" data-id="{{ $item->id }}" data-toggle="tooltip" title="Архивировать"><i
                                class="fas fa-trash"></i>@if ($cat == 'archive' || $cat == 'finish') Удалить @endif</a>
                    </div>

                    @if ($cat !== 'archive' && $cat !== 'finish')
                    <div class="col ml-3">
                        <a href="#" class="button button-gray btn-block" id="share" data-collapsed="{{ $item->collapsed }}" data-pinned="{{ $item->pinned }}" data-name="{{ $item->name }}" data-id="{{ $item->id }}" data-code="{{ $item->code }}" data-author="{{ $author_name }}" data-shared="{{ $item->shared }}" data-toggle="modal" data-target="#shareModal"><i
                                class="fas fa-user-plus"></i></a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @include('blocks.editorjs_view')

            @if ($item->shared !== \App\Models\Todo::SHARE_PRIVATE)
            <div class="text-muted small row no-gutters mt-2">
                <div class="col-12 col-md">
                    Просмотров: {{ $item->views }}
                </div>
                <div class="col-12 col-md-auto mr-auto">
                    Последнее обновление {{ date('d.m.Y в H:i', strtotime($item->updated_at)) . ' (UTC ' . date('P') . ')' }}
                </div>
            </div>            
            @endif
            
        </div>

    @endforeach
@endif

