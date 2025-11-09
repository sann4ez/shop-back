@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header')
    <section class="content">

        <div class="row">
            <div class="col-md-4">

                <div class="card"  style="position: sticky; top: 20px">
                    <div class="card-header">
                        <h3 class="card-title">Створити Атрибут</h3>
                        <div class="card-tools">
                            @can('dev')
                            {!! Lte3::formOpen([
                                'action' => route('admin.attributes.import'),
                                'files' => true,
                                'method' => 'POST',
                                'class' => 'js-form-submit-file-changed',
                                'style' => 'display: inline-flex',
                            ]) !!}
                            <label class="btn btn-default btn-xs m-0 bg-cyan"  data-toggle="tooltip" title="Імпорт атрибутів">
                                <i class="fas fa-download"></i>
                                <span style="font-weight: normal">Імпорт</span>
                                <input type="file" name="file" style="display: none;" accept=".csv,.xlsx">
                                <!-- Додаємо accept=".xlsx" для обмеження формату файлів -->
                            </label>
                            {!! Lte3::formClose() !!}
                            <a href="{{ \Illuminate\Support\Facades\Request::fullUrlWithQuery(['_export' => 'csv']) }}"
                               class="btn btn-default btn-xs bg-cyan" data-toggle="tooltip" title="Експорт атрибутів"><i
                                    class="fas fa-upload"></i> Експорт</a>
                            @endcan
                        </div>
                    </div>
                    {!! Lte3::formOpen(['action' => route('admin.attributes.store'), 'model' => null, 'method' => 'POST']) !!}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Lte3::text('name', null, ['label' => 'Назва атрибуту', 'placeholder' => 'Наприклад: Колір']) !!}
                            </div>
                            {{--
                            <div class="col-md-6">
                                {!! Lte3::text('prefix', null, ['label' => 'Префікс', 'placeholder' => 'milli']) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Lte3::text('suffix', null, ['label' => 'Суфікс', 'placeholder' => 'kg']) !!}
                            </div>
                            --}}
                        </div>

                        @foreach(\App\Models\Attribute::usesList('*', 'key') as $use)
                            {!! Lte3::checkbox($use['column'], null, [
                                'label' => "<i class='{$use['fa_icon']}'></i> {$use['label']}",
                                'default' => $use['default'] ?? false,
                                'class_control' => 'custom-switch',
                                'unchecked_value' => 0,
                                'checked_value' => 1,
                            ]) !!}
                        @endforeach
                    </div>
                    <div class="card-footer text-right">
                        {!! Lte3::btnSubmit('Зберегти') !!}
                    </div>
                    {!! Lte3::formClose() !!}
                </div>
            </div>

            <div class="col-md-8">
                @if($attributes->count())
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Всього: {{ $attributes->count() }}</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th style="width: 65px"></th>
                                <th>Назва</th>
                                {{--<th>Префікс</th>
                                <th>Суфікс</th>--}}
                                <th class="text-center" style="width: 200px">Опції</th>
                            </tr>
                            </thead>
                            <tbody class="sortable-y" data-url="{{ route('admin.attributes.order') }}">
                            @foreach($attributes as $attribute)
                                <tr id="{{ $attribute->id }}" class="va-center">
                                    <td title="{{ $attribute->slug }}">
                                        <div class="btn-actions dropdown">
                                            <button type="button" class="btn btn-sm btn-default cursor-move" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                            <div class="dropdown-menu" role="menu" style="top: 93%;">
                                                <a href="{{ route('admin.properties.index', ['attribute_id' => $attribute->id]) }}" class="dropdown-item">Властивості</a>
                                                <div class="dropdown-divider"></div>

                                                <a href="{{ route('admin.attributes.destroy', $attribute) }}"
                                                   class="dropdown-item js-click-submit" data-method="delete"
                                                   data-confirm="Видалити?">Видалити</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {!! Lte3::xEditable('name', $attribute->name, [
                                            'type' => 'text',
                                            'pk' => $attribute->id,
                                            'url_save' => route('admin.attributes.editable', $attribute),
                                        ]) !!}
                                        <a href="{{ route('admin.properties.index', ['attribute_id' => $attribute->id]) }}" class="hover-edit">[ {{ $attribute->properties_count }} ]</a>
                                    </td>
                                    {{--
                                    <td>
                                        {!! Lte3::xEditable('prefix', $attribute->prefix, [
                                            'type' => 'text',
                                            'pk' => $attribute->id,
                                            'url_save' => route('admin.attributes.editable', $attribute),
                                        ]) !!}
                                    </td>
                                    <td>
                                        {!! Lte3::xEditable('suffix', $attribute->suffix, [
                                            'type' => 'text',
                                            'pk' => $attribute->id,
                                            'url_save' => route('admin.attributes.editable', $attribute),
                                        ]) !!}
                                    </td>
                                    --}}
                                    <td>
                                        @foreach(\App\Models\Attribute::usesList('*', 'key') as $column => $data)
                                            <div title="{{ $column }}">
                                                {!! Lte3::checkbox("{$column}[{$attribute->id}]", $attribute->{$column} ? '1' : '0', [
                                                      'label' => "<i class='{$data['fa_icon']}'></i> {$data['label']}",
                                                      'class_control' => 'custom-switch',
                                                      'format' => 'name,value', // 'name,value'
                                                      'raw_name' => $column,
                                                      'url_save' => route('admin.attributes.editable', $attribute),
                                                  ]) !!}
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                    @include('admin.parts.empty-rows')
                @endif
            </div>
        </div>
    </section>
@stop

