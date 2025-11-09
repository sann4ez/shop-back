@extends('admin.parts.filter-wrap2')

@section('body')
    {!! Lte3::hidden('_by', 'id') !!}
    <div class="row">
        <div class="col-md-4">
            {!! Lte3::text('q', request('q'), [
                'label' => 'Пошук',
                'class_wrap' => 'js-barcode-scan-field',
                'append' => ['<button type="button" class="fas fa-barcode js-barcode-scan"></button>'],
            ]) !!}
        </div>
{{--        @if(\Domain::getOptIs(['products.fields.category', 'products.fields.categories']))--}}
{{--        <div class="col-md-4">--}}
{{--            {!! Lte3::select2Tree('categories', [--}}
{{--              'label' => 'Категорія',--}}
{{--              'multiple' => 1,--}}
{{--              'method_get' => 'GET',--}}
{{--              'url_tree' => route('admin.suggest.terms', [--}}
{{--                   'vocabulary' => \App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES,--}}
{{--                   'selected' => request('categories'),--}}
{{--                   'format' => 'treeselect',--}}
{{--               ]),--}}
{{--           ]) !!}--}}
{{--        </div>--}}
{{--        @endif--}}
{{--        @if(\Domain::getOptIs('products.fields.brand'))--}}
{{--        <div class="col-md-4">--}}
{{--            {!! Lte3::select2('brands', request('brands'), \App\Models\Term::byVocabulary(\App\Models\Term::VOCABULARY_BRANDS)->with('translations')->get()->pluck('name', 'id')->toArray(), [--}}
{{--              'label' => 'Бренд',--}}
{{--              'multiple' => 1,--}}
{{--           ]) !!}--}}
{{--        </div>--}}
{{--        @endif--}}
    </div>
@stop

