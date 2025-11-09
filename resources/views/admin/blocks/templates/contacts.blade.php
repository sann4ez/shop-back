{!! Lte3::hidden('notarrays', 'map,payments,shippings') !!}

<div class="row">
    <div class="col-md-6">{!! Lte3::text('content[phone]', null, ['label' => 'Телефон']) !!}</div>
    <div class="col-md-6">{!! Lte3::email('content[email]', null, ['label' => 'Електронна адреса']) !!}</div>
</div>
<div class="row">
    <div class="col">{!! Lte3::textarea('content[address]', null, ['label' => 'Адреса магазину']) !!}</div>
    <div class="col">{!! Lte3::textarea('content[schedule]', null, ['label' => 'Графік роботи']) !!}</div>
</div>

<div class="row">
    <div class="col">{!! Lte3::url('content[map][link]', null, ['label' => 'Google-map посилання']) !!}</div>
    <div class="col">{!! Lte3::text('content[map][lat]', null, ['label' => 'Map Latitude']) !!}</div>
    <div class="col">{!! Lte3::text('content[map][lng]', null, ['label' => 'Map Longitude']) !!}</div>
    <div class="col">{!! Lte3::number('content[map][zoom]', null, ['label' => 'Map Zoom']) !!}</div>
</div>

<div class="row">
    <div class="col">{!! Lte3::textarea('content[map][iframe]', null, ['label' => 'Google-map iFrame']) !!}</div>
    <div class="col">{!! Lte3::textarea('content[copyright]', null, ['label' => 'Підвал (копірайт)']) !!}</div>
</div>

<div class="row">
    <div class="col">{!! Lte3::url('content[terms]', null, ['label' => 'Умови використання']) !!}</div>
    <div class="col">{!! Lte3::url('content[policy]', null, ['label' => 'Політика конфіденційності']) !!}</div>
</div>

{{-- MULTYITEMS: Phones--}}
<div class="card f-wrap f-multyblocks">
    <div class="card-header"><h3 class="card-title">Телефони:</h3></div>
    <div class="card-body">
        <div class="f-items sortable-y" data-input-weight-class="js-input-weight">

            {{-- TEMPLATE MULTIPLE FIELDS --}}
            <template class="f-item-template">
                <div class="f-item">
                    <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                                class="fa fa-trash"></i></a>
                    <i class="fa fa-arrows-alt-v cursor-move"></i>
                    <div class="row">
                        <div class="col-md-4">{!! Lte3::text('content[phones][$i][number]', null, ['label' => 'Номер',]) !!}</div>
                        <div class="col-md-4">{!! Lte3::text('content[phones][$i][operator]', null, ['label' => 'Оператор',]) !!}</div>
                        <div class="col-md-4">{!! Lte3::hidden('content[phones][$i][weight]', null, ['class' => 'js-input-weight',]) !!}</div>
                    </div>
                </div>
            </template>

            {{-- ALREADY SAVED MULTIPLE FIELDS --}}
            @if (isset($block) && ($items = $block->getContentSort('phones', [])))
                @foreach ($items as $item)
                    <div class="f-item">
                        <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                                    class="fa fa-trash"></i></a>
                        <i class="fa fa-arrows-alt-v cursor-move"></i>
                        <div class="row">
                            <div class="col-md-4">{!! Lte3::text("content[phones][{$loop->index}][number]", $item['number'] ?? '', ['label' => 'Номер']) !!}</div>
                            <div class="col-md-4">{!! Lte3::text("content[phones][{$loop->index}][operator]", $item['operator'] ?? '', ['label' => 'Оператор']) !!}</div>
                            <div class="col-md-4">{!! Lte3::hidden("content[phones][{$loop->index}][weight]", $item['weight'] ?? 0, ['class' => 'js-input-weight']) !!}</div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="js-msg-empty">Елементів не додано 😢</p>
            @endisset
        </div>
        <a href="" class="btn btn-info btn-xs float-right js-btn-add"><i class="fa fa-plus"></i></a>
    </div>
</div>

{{-- MULTYITEMS: Socials--}}
<div class="card f-wrap f-multyblocks">
    <div class="card-header"><h3 class="card-title">Соціальні мережі:</h3></div>
    <div class="card-body">
        <div class="f-items sortable-y" data-input-weight-class="js-input-weight">

            {{-- TEMPLATE MULTIPLE FIELDS --}}
            <template class="f-item-template">
                <div class="f-item">
                    <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                                class="fa fa-trash"></i></a>
                    <i class="fa fa-arrows-alt-v cursor-move"></i>
                    <div class="row">
                        <div class="col-md-4">{!! Lte3::text('content[socials][$i][name]', null, ['label' => 'Заголовок',]) !!}</div>
                        <div class="col-md-4">{!! Lte3::text('content[socials][$i][url]', null, ['label' => 'Посилання',]) !!}</div>
                        <div class="col-md-4">{!! Lte3::text('content[socials][$i][icon]', null, ['label' => 'Іконка',]) !!}</div>
                    </div>
                    {!! Lte3::hidden('content[socials][$i][weight]', null, ['class' => 'js-input-weight',]) !!}

                </div>
            </template>

            {{-- ALREADY SAVED MULTIPLE FIELDS --}}
            @if (isset($block) && ($items = $block->getContentSort('socials', [])))
                @foreach ($items as $item)
                    <div class="f-item">
                        <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                                    class="fa fa-trash"></i></a>
                        <i class="fa fa-arrows-alt-v cursor-move"></i>
                        <div class="row">
                            <div class="col-md-4">{!! Lte3::text("content[socials][{$loop->index}][name]", $item['name'] ?? '', ['label' => 'Заголовок']) !!}</div>
                            <div class="col-md-4">{!! Lte3::text("content[socials][{$loop->index}][url]", $item['url'] ?? '', ['label' => 'Посилання']) !!}</div>
                            <div class="col-md-4">{!! Lte3::text("content[socials][{$loop->index}][icon]", $item['icon'] ?? '', ['label' => 'Іконка']) !!}</div>
                        </div>
                        {!! Lte3::hidden("content[socials][{$loop->index}][weight]", $item['weight'] ?? 0, ['class' => 'js-input-weight']) !!}
                    </div>
                @endforeach
            @else
                <p class="js-msg-empty">Елементів не додано 😢</p>
            @endisset
        </div>
        <a href="" class="btn btn-info btn-xs float-right js-btn-add"><i class="fa fa-plus"></i></a>
    </div>
</div>

@if($paymentsList = shop_payments())
<div class="card">
    <div class="card-header"><h3 class="card-title">Способи оплати</h3></div>
    <div class="card-body">
        <div class="row">
            @foreach($paymentsList as $payment)
                <div class="col-md-4">
                    {!! Lte3::text("content[payments][{$payment['key']}][title]", null, ['label' => "{$payment['name']} (Назва)"]) !!}
                    {!! Lte3::textarea("content[payments][{$payment['key']}][desc]", null, ['label' => "{$payment['name']} (Опис)"]) !!}
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif


@if($shippings = shop_shippings())
<div class="card">
    <div class="card-header"><h3 class="card-title">Способи доставки</h3></div>

    <div class="card-body">
        <div class="row">
            @foreach($shippings as $shipping)
                <div class="col-md-4">
                    {!! Lte3::text("content[shippings][{$shipping['key']}][title]", null, ['label' => "{$shipping['name']} (Назва)"]) !!}
                    {!! Lte3::textarea("content[shippings][{$shipping['key']}][desc]", null, ['label' => "{$shipping['name']} (Опис)"]) !!}
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
