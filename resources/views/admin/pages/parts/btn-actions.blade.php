<div class="btn-actions dropdown">
    <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
    <div class="dropdown-menu {{ $dropdownClass ?? '' }}" role="menu" style="top: 93%;">
{{--        @can('page.update')--}}
            <a href="{{ route('admin.pages.edit', $page) }}" class="dropdown-item">Редагувати</a>
{{--        @endcan--}}
        <div class="dropdown-divider"></div>
{{--        @can('page.delete')--}}
            <a href="{{ route('admin.pages.destroy', $page) }}"
               class="dropdown-item js-click-submit" data-method="delete"
               data-confirm="Видалити?">Видалити</a>
{{--        @endcan--}}
    </div>
</div>
