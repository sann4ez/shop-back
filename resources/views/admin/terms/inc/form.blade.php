@php($vocabularySlug = request('vocabulary', $term->vocabulary ?? null))
<input type="hidden" name="vocabulary" value="{{ $vocabularySlug }}">
<input type="hidden" name="parent_id" value="{{ request('parent_id', $term->parent_id ?? null) }}">

<div class="row">
    <div class="col-lg-9">
        {!! Lte3::text('name', null, ['label' => 'Назва']) !!}

        {!! Lte3::textarea('body', null, [
            'label' => 'Контент',
            'rows' => 3,
            'class' => 'f-tinymce',
        ]) !!}
    </div>

    <div class="col-lg-3">

        @if(in_array($vocabularySlug, [\App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES, \App\Models\Term::VOCABULARY_POST_CATEGORIES, \App\Models\Term::VOCABULARY_BRANDS, \App\Models\Term::VOCABULARY_FAQ_CATEGORIES]))
        {!! Lte3::select2('status', null, \App\Models\Term::statusesList('name', 'key'), [
            'label' => 'Статус',
        ]) !!}
        @endif

        @if($vocabularySlug === \App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES)
        {!! Lte3::select2('attributes', isset($term) ? $term->attrs->pluck('id')->toArray() : [], \App\Models\Attribute::query()->orderBy('weight')->get()->pluck('name', 'id')->toArray(), [
            'label' => 'Атрибути варіацій',
            'empty_value' => '--',
            'multiple' => true,
            'help' => '* атрибути для варіації даної категораї (перемикання на сторінці, фільтрування) '
        ]) !!}
        @endif

        {{--  При зміни - запустити  variation:groped-attribute варіацій --}}
        @if($vocabularySlug === \App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES)
{{--            @if(\Domain::getOpt('variations.groped_type') === 'attribute')--}}
{{--            {!! Lte3::select2('added[attribute_groped]', isset($term) ? $term->getAdded('attribute_groped') : [], \App\Models\Attribute::query()->where('in_variant', true)->orderBy('weight')->get()->pluck('name', 'id')->toArray(), [--}}
{{--                'label' => 'Атрибут для групування варіацій в каталозі',--}}
{{--                'empty_value' => '--',--}}
{{--                'help' => '* варіації групи виводяться тільки по одному варіанту для кожного значеннями вказаного тут атрибуту'--}}
{{--            ]) !!}--}}
{{--            @endif--}}

{{--            @if(\Domain::getOptIs('products.fields.productparity'))--}}
{{--            {!! Lte3::select2('added[parity_attributes]', isset($term) ? $term->getAdded('parity_attributes') : [], \App\Models\Attribute::query()->where('in_variant', true)->orderBy('weight')->get()->pluck('name', 'id')->toArray(), [--}}
{{--                'label' => 'Атрибути для формування парних варіацій',--}}
{{--                'empty_value' => '--',--}}
{{--                'multiple' => true,--}}
{{--                'help' => '* парні варіації формується за однаковими значенням вказаних тут атрибутів (+моделі, якщо вказано) та відмінними категоріями парності'--}}
{{--            ]) !!}--}}
{{--            {!! Lte3::checkbox('added[only_parities]', isset($term) ? $term->getAdded('only_parities') : false, [--}}
{{--               'label' => 'Виводити/групувати в категорії лише варіації, які мають пари',--}}
{{--               'checked_value' => 1,--}}
{{--               'unchecked_value' => 0,--}}
{{--               'wrap_class' => 'custom-switch'--}}
{{--            ]) !!}--}}
{{--            @endif--}}

{{--            @if(\Domain::getOptIs("terms.vocabularies.{$vocabularySlug}.fields.icon"))--}}
{{--                {!! Lte3::select2('added[icon]', isset($term) ? $term->getAdded('icon', '') : '', \Domain::getOpt('terms.vocabularies.product_categories.icons', []), [--}}
{{--                    'label' => 'Іконка в головне меню', 'empty_value' => '--',--}}
{{--                ]) !!}--}}
{{--            @endif--}}
{{--            @if(\Domain::getOptIs("terms.vocabularies.{$vocabularySlug}.fields.logo"))--}}
{{--                {!! Lte3::mediaImage('logo', null, [--}}
{{--                    'label' => 'Логотип',--}}
{{--                ]) !!}--}}
{{--            @endif--}}
            {!! Lte3::mediaImage('image', null, [
                'label' => 'Зображення',
            ]) !!}
        @endif

    </div>

</div>

<div class="text-right">
    {!! Lte3::btnReset('Вийти') !!}
    {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
</div>
