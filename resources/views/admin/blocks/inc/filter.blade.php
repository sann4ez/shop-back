@extends('admin.parts.filter-wrap2')

@section('body')
    <div class="row">
        <div class="col-md-4">
            {!! Lte3::text('q', request('q'), [
                'label' => 'Назва',
            ]) !!}
        </div>
        <div class="col-md-4">
            {!! Lte3::select2('status', request('status'), \App\Models\Block::statusesList('name', 'key'), [
                'empty_value' => '--',
                'label' => 'Статус',
            ]) !!}
        </div>
        <div class="col-md-4">
            {!! Lte3::select2('models',request('models'), \App\Models\Block::getBlockableModels() + ['not_related' => 'Без привязки'], [
                'empty_value' => '--',
                'multiple' => true,
                'label' => 'Приєднання',
            ]) !!}
        </div>
    </div>
@stop

