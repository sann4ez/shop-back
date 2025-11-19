@extends('admin.layouts.app')

@section('content')

    @include('lte::layouts.inc.content-header', [
       'page_title' => trans('lte::main.Variables'),
    ])

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-header with-border" style="display:none;">
                        <h3 class="box-title">{{ trans('lte::main.Total') }}: 3 </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody>
                                <tr>
                                    <th style="width: 1%">#</th>
                                    <th style="width: 10%">Key</th>
                                    <th style="width: 55%">Value</th>
                                    <th style="width: 15%">Group</th>
                                </tr>
                                @foreach(\Variable::setGroup()->all() as $var)
                                <tr>
                                    <td title="{{ $var->id }}"></td>
                                    <td>{{ $var->key }}</td>
                                    <td>
                                        {!! $var->value !!}
                                        {{--
                                        @include('lte::fields.field-x-editable', [
                                          'value' => $var->value,
                                          'type' => 'textarea',
                                          'field_name' => 'data[message]',
                                          'pk' => 14,
                                          //'url' => route('lte.data.status'),
                                       ])
                                        --}}
                                    </td>
                                    <td>{{ $var->group }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer">
                        @include('lte::fields.field-form-buttons')
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
