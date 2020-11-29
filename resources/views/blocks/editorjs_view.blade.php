@php ($blocks = json_decode($item->data, true))
@if (count($blocks['blocks']) > 0)
    <div class="card card-body border-0 text-dark mt-2">
        @foreach ($blocks['blocks'] as $block)
            @if ($block['type'] == 'list')
                @php ($list = $block['data']['style'][0] . 'l')
                <{{ $list }} class="@if (!$loop->first) mt-2 @endif">
                    @foreach ($block['data']['items'] as $item)
                        <li class="mt-1">{!! $item !!}</li>
                    @endforeach
                </{{ $list }}>
            @elseif ($block['type'] == 'header')
                <span class="@if (!$loop->first) mt-2 @endif"><h{{ $block['data']['level'] }}>{!! $block['data']['text'] !!}</h{{ $block['data']['level'] }}></span>
            @else
                <span class="@if (!$loop->first) mt-2 @endif">{!! isset($block['data']['text']) ? $block['data']['text'] : '' !!}</span>
            @endif
        @endforeach
    </div>
@endif