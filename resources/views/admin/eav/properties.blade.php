@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => "Значення атрибуту <strong>{$attribute->name}</strong>",
        'url_back' => route('admin.attributes.index', ['attribute_slug' => $attribute->slug]),
    ])

    <section class="content">
        <div class="row">
            <div class="col-lg-4" style="position: sticky; top: 20px">
                {!! Lte3::formOpen(['action' => route('admin.properties.store'), 'model' => null, 'method' => 'POST']) !!}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Створити значення <strong>{{ $attribute->name }}</strong></h3>
                    </div>
                    <div class="card-body">
                        {!! Lte3::hidden('attribute_slug', $attribute->slug) !!}
                        {!! Lte3::hidden('attribute_id', $attribute->id) !!}
                        {!! Lte3::text('value', null, ['label' => 'Значення', 'placeholder' => 'Наприклад: Червоний']) !!}
                        {{--
                        <div class="row">
                            <div class="col-md-6">
                                {!! Lte3::text('prefix') !!}
                            </div>
                            <div class="col-md-6">
                                {!! Lte3::text('suffix') !!}
                            </div>
                        </div>
                        --}}
                        @if($attribute->has_image)
                            {!! Lte3::colorpicker('color', null, ['label' => 'Color', 'default' => '#FFFFFF']) !!}
                            {!! Lte3::file('image', null, ['label' => 'Зображення']) !!}
                        @endif
                    </div>
                    <div class="card-footer text-right">
                        {!! Lte3::btnSubmit('Зберегти') !!}
                    </div>
                </div>
                {!! Lte3::formClose() !!}

            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Всього: {{ $properties->count() }}</h3>
                    </div>
                    <div class="card-body table-responsive p-0" >
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th style="width: 65px"></th>
                                <th>Значення</th>
                                {{--
                                <th>{{ trans('lte::main.Prefix') }}</th>
                                <th>{{ trans('lte::main.Suffix') }}</th>
                                --}}
                                @if($attribute->has_image)
                                    <th class="text-center">Колір</th>
                                    <th class="text-center">Зображення</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="sortable-y" data-url="{{ route('admin.properties.order') }}">
                            @foreach($properties as $property)
                                <tr id="{{ $property->id }}" class="va-center">
                                    <td title="{{ $property->slug }}">
                                        <div class="btn-actions dropdown">
                                            <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                            <div class="dropdown-menu" role="menu" style="top: 93%;">
                                                <a href="{{ route('admin.properties.destroy', $property) }}"
                                                   class="dropdown-item js-click-submit" data-method="delete"
                                                   data-confirm="Видалити?">Видалити</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {!! Lte3::xEditable('value',  $property->value, [
                                            'pk' => $property->id,
                                            'url_save' => route('admin.properties.editable', $property),
                                        ]) !!}
                                    </td>
                                    {{--
                                    <td>
                                        {!! Lte3::xEditable('prefix',  $property->prefix, [
                                            'type' => 'text',
                                            'pk' => $property->id,
                                            'url_save' => route('admin.properties.editable', $property),
                                        ]) !!}
                                    </td>
                                    <td >
                                        {!! Lte3::xEditable('suffix',  $property->suffix, [
                                            'type' => 'text',
                                            'pk' => $property->id,
                                            'url_save' => route('admin.properties.editable', $property),
                                        ]) !!}
                                    </td>
                                    --}}

                                    @if($attribute->has_image)
                                    <td class="text-center" style="width: 200px">
                                        {!! Lte3::colorpicker('color', $property->color, ['label' => '', 'url_save' => route('admin.properties.editable', $property),]) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! Lte3::fileForm('image', [
                                            'label' => 'Вибрати <i class="fas fa-download"></i>',
                                            'html' => ($img = $property->getFirstMediaUrl('image', 'thumb')) ? '<div><a href="'.$img.'" class="js-popup-image"><img src="'.$img.'" style="width: 100px;"></a></div>' : '',
                                            'url_save' => route('admin.properties.image', $property),
                                      ]) !!}
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
