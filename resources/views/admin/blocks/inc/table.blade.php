<table class="table table-hover ">
    <thead>
        <tr>
            <th style="width: 65px"></th>
            <th>Назва</th>
            <th>Тип</th>
            <th>Cлаґ</th>
            {{--<th>Приєднано</th>--}}
            <th class="ha-center">Опубліковано</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($blocks as $block)
            <tr id="{{ $block->id }}" class="va-center">
                <td>
                    <div class="btn-actions dropdown">
                        <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                        <div class="dropdown-menu" role="menu" style="top: 93%;">
                            <a href="{{ route('admin.blocks.show', $block) }}" class="dropdown-item js-modal-fill-html"
                               data-target='#modal-lg' data-fn-inits="initPopupImage">Переглянути</a>

                            <a href="{{ route('admin.blocks.edit', $block) }}" class="dropdown-item">Редагувати</a>

                            <a href="{{ route('admin.blocks.cloning', $block) }}" class="dropdown-item">Клонувати</a>
                            <div class="dropdown-divider"></div>

                            <a href="{{ route('admin.blocks.destroy', $block) }}"
                               class="dropdown-item js-click-submit" data-method="delete"
                               data-confirm="Видалити?">Видалити</a>
                        </div>
                    </div>
                </td>
                <td>
                    <a href="{{ route('admin.blocks.edit', $block) }}" class="hover-edit"
                       title="{{ $block->id }}">{{ $block->name }}</a>
                    <br><small>Оновлено {{ $block->getDatetime('updated_at') }}</small>
                </td>
                <td>{{ $block->type }}</td>
                <td>{{ $block->slug }}</td>
                {{--<td>{{ $block->getRelatedModels() }}</td>--}}
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
