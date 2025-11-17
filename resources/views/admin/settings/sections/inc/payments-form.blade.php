<div class="col-md-6">
    {!! Lte3::formOpen(['action' => route('admin.services.update', $service), 'method' => 'PUT']) !!}
    <div class="card card-info card-solid">
        <div class="card-header with-border p-1"></div>
        <div class="card-body">
            {!! Lte3::checkbox('options[active]', $service->getOpt('active'), [
                'id' => Str::orderedUuid(),
                'label' => 'Активно',
                'class_control' => 'custom-switch',
            ]) !!}
            @foreach ($fields as $field)
                {!! Lte3::field([
                    'value' => $service->getOpt($field['name']),
                    'name' => "options[{$field['name']}]",
                ] + $field) !!}
            @endforeach
        </div>
        <div class="card-footer text-right">
            @if($canDelete ?? false)
                <a href="{{ route('admin.services.destroy', $service) }}" class="btn btn-outline-danger js-click-submit" data-method="delete" data-confirm="Видалити?">Видалити</a>
            @endif
            {!! Lte3::btnSubmit('Зберегти') !!}
        </div>
    </div>
    {!! Lte3::formClose() !!}
</div>
<?php
