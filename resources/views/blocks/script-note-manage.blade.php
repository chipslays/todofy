@php ($cat = app('request')->input('category', null))
@section('script')
<script type="text/javascript">
    $(document).on('click', '#manage', function() {
        swal({
            title: "Вы уверены, что",
            text: "хотите подтвердить это действие?",
            dangerMode: true,
            buttons: ['Отмена', 'Да'],
        })
        .then((doIt) => {
            if (doIt) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    url: `/note/` + $(this).data('method').trim() + `/` + $(this).data('id') + `?category={{ isset($is_author) ? 'public' : $cat }}`,
                    method: 'GET',
                })
                .done(function(data) {
                    if (data.ok) {
                        $("#items").html(data.html);
                    } else {
                        console.log(data);
                        swal("Ой, непредвиденная ошибка!", {
                            icon: "error",
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '#share', function() {
        var link = window.location.origin + `/@` + $(this).data('author') + `/` + $(this).data("code");
        $("#link").html(link).prop('href', link);
        $("#shareLink").val(window.location.origin + `/@` + $(this).data('author') + `/`);
        $("#shareCode").val($(this).data("code"));
        $("#shareCode").data('author', $(this).data('author'));
        $('#shareSelect').val($(this).data("shared"));
        $('#shareName').html($(this).data("name"));
        $("#shareApply").data('id', $(this).data("id"));
        $("#shareApply").data('old-code', $(this).data("code"));

        $("#pinned").prop('checked', $(this).data('pinned') == 1);
        $("#collapsed").prop('checked', $(this).data('collapsed') == 1);
    });

    $('#shareCode').on('input',function () {
        var link = window.location.origin + `/@` + $(this).data('author') + `/` + $(this).val();
        $("#link").html(link).prop('href', link);
    });

    $("#shareApply").click(function () {
        var oldCode = $(this).data('old-code');
        var newCode = $("#shareCode").val().trim();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            url: `/note/share/` + $(this).data('id'),
            method: 'GET',
            data: {
                pinned: $("#pinned").prop('checked'), 
                collapsed: $("#collapsed").prop('checked'),
                type: $('#shareSelect option:selected').val(),
                category: {!! isset($is_author) ? "'public'" : "'{$cat}'" ?? "'active'" !!},
                old_code: oldCode,
                new_code: newCode,
            },
        })
        .done(function(data) {
            if (data.ok) {
                $("#items").html(data.html);
                swal(data.alert.text, {
                    icon: data.alert.icon,
                });

                if (!data.alert.error) {
                    $(this).data('old-code', newCode);
                }
            } else {
                console.log(data);
                swal("Ой, непредвиденная ошибка!", {
                    icon: "error",
                });
            }
        });
    });
</script>
@endsection
