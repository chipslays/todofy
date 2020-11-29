<div class="col-12 col-md-8 col-lg-6 order-3 order-lg-2 @guest offset-md-3 @endguest">
    <div class="container p-0">
        <section class="my-3">
            <div class="font-weight-bold mb-2">
                @if ($shared ?? false)
                    Заметки <a href="{{ route("user_notes", ['username' => $author_name]) }}" class="text-primary text-decoration-none">{{ '@' . $author_name }}</a>
                @else
                    Список
                @endif
            </div>
            
            <div class="" id="items">
                @include('blocks.list_items')
            </div>

            <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header">
                            <div id="shareName" class="h6 p-0 m-0 font-weight-bold"></div>
                        </div>
                        <div class="modal-body overflow-dot">
                            <div class="text-muted mb-0">Постоянная ссылка</div>
                            <a href="#" target="_blank" class="small text-decoration-none font-weight-bold mt-0" id="link"></a>

                            <div class="text-muted mt-3 mb-0">Уникальный код заметки</div>
                            <input class="form-control form-control-sm" type="text" id="shareCode" value="" placeholder="Уникальный код заметки">

                            <div class="text-muted mt-3 mb-0">Тип доступа</div>
                            <select class="form-control form-control-sm" id="shareSelect">
                                <option value="{{ \App\Models\Todo::SHARE_PRIVATE }}" selected>Приватная</option>
                                <option value="{{ \App\Models\Todo::SHARE_LINK }}">Публичная (доступ по ссылке)</option>
                                <option value="{{ \App\Models\Todo::SHARE_USERNAME }}" disabled>Доступ по имени пользователя</option>
                            </select>

                            <div class="text-muted mt-3">Настройки заметки</div>
                            <div class="custom-control custom-checkbox text-muted mt-1">
                                <input type="checkbox" class="custom-control-input" id="pinned">
                                <label class="custom-control-label" for="pinned">Закреплена</label>
                            </div>

                            <div class="custom-control custom-checkbox text-muted">
                                <input type="checkbox" class="custom-control-input" id="collapsed">
                                <label class="custom-control-label" for="collapsed">Раскрыта</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="m-2 button button-gray" data-dismiss="modal">Отмена</a>
                            <a href="#" class="m-2 button button-blue" id="shareApply">Сохранить</a>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>

