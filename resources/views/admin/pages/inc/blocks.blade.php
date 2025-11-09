<div class="mb-3">
    <a href="#"
       class="btn btn-info btn-flat"
       data-toggle="modal"
       data-target="#modal-attach-blocks"
    ><i class="fa fa-link"></i> Приєднати уже існуючі</a>

    <div class="btn-group">
        <button type="button" class="btn btn-flat btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-plus"></i> Створити і приєднати нові
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" role="menu" style="max-height: 500px; overflow: scroll">
            @foreach (\App\Models\Block::typesList() as $item)
                <a class="dropdown-item" href="{{ route('admin.blocks.create', ['type' => $item['key'], 'model_type' => 'page', 'model_id' => $page->id, '_back' => \Request::fullUrl()]) }}">
                    {{ $item['name'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>

@php($blocks = $page->blocks->load('translations'))
@if($blocks->count())
<table class="table table-hover">
    <thead>
        <tr>
            <th style="width: 65px"></th>
            <th>Назва</th>
            <th>Тип</th>
            <th>Cлаґ</th>
            <th class="ha-center">Опубліковано</th>
        </tr>
    </thead>
    <tbody class="sortable-y" data-url="{{ route('admin.pages.blocks.order', $page) }}">
        @foreach($blocks as $block)
            <tr class="va-center" id="{{ $block->pivot->id }}">
                <td>
                    <div class="btn-actions dropdown">
                        <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                        <div class="dropdown-menu" role="menu" style="top: 93%;">
                            <a href="{{ route('admin.blocks.edit', [$block, 'type' => $block->type, '_back' => \Request::fullUrl()]) }}" class="dropdown-item">Редагувати</a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('admin.pages.blocks.detach', [$page, 'ids' => $block->pivot->id]) }}"
                               class="dropdown-item js-click-submit" data-method="post" data-confirm="Confirm?">Відєднати</a>
                        </div>
                    </div>
                </td>
                <td>
                    <a class="hover-edit"
                        href="{{ route('admin.blocks.edit', [$block, 'type' => $block->type, '_back' => \Request::fullUrl()]) }}">{{ $block->name ?: 'Редагувати' }}</a>
                    <br />
                    <small>Створено: {{ $block->getDatetime('created_at') }}</small>
                </td>
                <td>{{ $block->type }}</td>
                <td>{{ $block->slug }}</td>
                <td class="ha-center">
                    @if ($block->status === \App\Models\Block::STATUS_PUBLISHED)
                        <i class="far fa-check-circle text-green" title="Опубліковано" data-toggle="tooltip"></i>
                    @else
                        <i class="far fa-circle text-warning" title="Приховано" data-toggle="tooltip"></i>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@else
    @include('admin.parts.empty-rows')
@endif

@push('modals')
    <div class="modal fade" id="modal-attach-blocks">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                {!! Lte3::formOpen(['action' => route('admin.pages.blocks.attach', $page), 'method' => 'POST']) !!}
                <div class="modal-header">
                    <h4 class="modal-title">Вказати блоки для приєднання</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {!! Lte3::select2('ids', null, \App\Models\Block::with('translations')->latest()->get()->pluck('name', 'id')->toArray(), [
                        'label' => 'Блоки',
                        'multiple' => true,
                    ]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! Lte3::checkbox('cloning', false, [
                               'label' => 'Клонувати та приєднати',
                               'class_control' => 'custom-switch',
                               'help' => '* будуть створені нові екземпляри вибраних блоків, які можна індивідуально редагувати'
                           ]) !!}
                        </div>

                        <div class="col-md-6">
                            {!! Lte3::checkbox('may_unique_slug', false, [
                               'label' => 'При клонуванні генерувати унікальні слаги',
                               'class_control' => 'custom-switch',
                           ]) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    {!! Lte3::btnModalClose('Закрити') !!}
                    {!! Lte3::btnSubmit('Зберегти') !!}
                </div>
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </div>
@endpush
