@php
    if (\Request::route()->getName() === 'catalog.search') {
        $min = $facet['prices']['search']['from'] ?? 0;
        $max = ($facet['prices']['search']['to'] ?? 10000) == 0 ? $facet['prices']['max'] ?? 10000 : $facet['prices']['search']['to'] ?? 10000;
    } else {
        $min = $facet['prices']['category']['from'] ?? 0;
        $max = ($facet['prices']['category']['to'] ?? 10000) == 0 ? $facet['prices']['max'] ?? 10000 : $facet['prices']['category']['to'] ?? 10000;
    }
@endphp
<form action="{{ \Request::url() }}" class="filter" method="GET">
    <input type="hidden" name="q" value="{{ request('q') ?: null }}" autocomplete="off">
    <div class="filter__content">
        <div class="filter__header">
            <button type="button" class="arrow  arrow--pagination arrow--modal arrow--filter filter-dropdown__btn js-filter-btn-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">
                    <rect x="32" y="32.5" width="32" height="32" rx="16" transform="rotate(-180 32 32.5)" fill="#E9E8E8"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M18.2069 24.707L9.99977 16.4999L18.2069 8.29282L19.6211 9.70704L12.8282 16.4999L19.6211 23.2928L18.2069 24.707Z" fill="#121212"/>
                </svg>
            </button>
            <h1 class="title filter-dropdown__title">Фільтр</h1>
        </div>
        <div class="filter__wrapper">
            @if(isset($facet['brands']) && count($facet['brands']))
                <div class="accordion-item accordion-item--filter">
                    <h2 class="accordion-header filter__title " id="panelsStayOpen-headingOne">
                        <button class="accordion-button accordion-button--filter main-text main-text--semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                            <span class="filter__accordion-header">
                                Виробник
                            </span>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 16 17"
                                fill="none"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M0.511963 6.06955L1.48815 4.93066L8.00006 10.5123L14.512 4.93066L15.4881 6.06955L8.00006 12.4879L0.511963 6.06955Z"
                                    fill="#121212"
                                />
                            </svg>
                        </button>

                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show filter__select-wrapper" aria-labelledby="panelsStayOpen-headingOne">
                        @foreach($facet['brands'] as $brand)
                        @if($brand->is_allowed)
                        <label class="filter__select-label filter__select-label--modal js-click-url"
                               data-url="{{ \FacetFilter::build('brands', $brand->slug) }}">
                        <input name="brand" class="filter__select-input" type="checkbox"
                               @if(\FacetFilter::has('brands', $brand->slug)) checked @endif
                               id="prop-{{$brand->id}}"
                               autocomplete="off"
                        >
                        <span class="filter__select-input-img">

                        <svg class="filter__select-input-svg" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                            <rect width="25" height="25" rx="5" fill="#2A8927"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M21.7072 6.70712L9.00008 19.4142L3.29297 13.7071L4.70718 12.2929L9.00008 16.5858L20.293 5.29291L21.7072 6.70712Z" fill="#F5F5F5"/>
                        </svg>
                        </span>
                                {{ $brand->name }}
                        </label>
{{--                        @else--}}
{{--                        <label class="filter__select-label filter__select-label--modal filter__select-label--disabled" disabled>--}}
{{--                            <input name="brand" class="filter__select-input" type="checkbox"--}}
{{--                                   id="prop-{{$brand->id}}">--}}
{{--                            <span class="filter__select-input-img">--}}

{{--                        <svg class="filter__select-input-svg" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">--}}
{{--                            <rect width="25" height="25" rx="5" fill="#2A8927"/>--}}
{{--                            <path fill-rule="evenodd" clip-rule="evenodd" d="M21.7072 6.70712L9.00008 19.4142L3.29297 13.7071L4.70718 12.2929L9.00008 16.5858L20.293 5.29291L21.7072 6.70712Z" fill="#F5F5F5"/>--}}
{{--                        </svg>--}}
{{--                        </span>--}}
{{--                            {{ $brand->name }}--}}
{{--                        </label>--}}
                        @endif
                        @endforeach
                    </div>
                </div>
                <span class="filter__devider"></span>
            @endif
            @foreach(\Arr::get($facet, 'attributes', []) as $attr)
                @if($attr->is_allowed)
                    <div class="accordion-item accordion-item--filter">
                        <h2 class="accordion-header filter__title " id="panelsStayOpen-heading{{ $attr->id }}">
                            <button type="button" class="accordion-button accordion-button--filter main-text main-text--semibold" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse{{ $attr->id }}" aria-expanded="true" aria-controls="panelsStayOpen-collapse{{ $attr->id }}">
                                <span class="filter__accordion-header">
                                    {{ $attr->getName() }}
                                </span>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 16 17"
                                    fill="none"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M0.511963 6.06955L1.48815 4.93066L8.00006 10.5123L14.512 4.93066L15.4881 6.06955L8.00006 12.4879L0.511963 6.06955Z"
                                        fill="#121212"
                                    />
                                </svg>
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapse{{ $attr->id }}" class="accordion-collapse collapse show filter__select-wrapper" aria-labelledby="panelsStayOpen-heading{{ $attr->id }}">
                            @foreach($attr->properties as $prop)
                            @if($prop->is_allowed)
                            <label class="filter__select-label filter__select-label--modal js-click-url"
                                   data-url="{{ \FacetFilter::build($attr->slug, $prop->slug) }}">
                                <input name="brand"
                                       class="filter__select-input "
                                       type="checkbox"
                                       @if(\FacetFilter::has($attr->slug, $prop->slug)) checked @endif
                                       id="prop-{{$prop->id}}"
                                       autocomplete="off"
                                >
                                <span class="filter__select-input-img">
                                    <svg class="filter__select-input-svg" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                        <rect width="25" height="25" rx="5" fill="#2A8927"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.7072 6.70712L9.00008 19.4142L3.29297 13.7071L4.70718 12.2929L9.00008 16.5858L20.293 5.29291L21.7072 6.70712Z" fill="#F5F5F5"/>
                                    </svg>
                                </span>
                                {{ $prop->getValue() }}
                            </label>
{{--                            @else--}}
{{--                            <label class="filter__select-label filter__select-label--modal filter__select-label--disabled" disabled>--}}
{{--                                <input name="brand"--}}
{{--                                       class="filter__select-input "--}}
{{--                                       type="checkbox"--}}
{{--                                       id="prop-{{$prop->id}}">--}}
{{--                                <span class="filter__select-input-img">--}}
{{--                                    <svg class="filter__select-input-svg" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">--}}
{{--                                        <rect width="25" height="25" rx="5" fill="#2A8927"/>--}}
{{--                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.7072 6.70712L9.00008 19.4142L3.29297 13.7071L4.70718 12.2929L9.00008 16.5858L20.293 5.29291L21.7072 6.70712Z" fill="#F5F5F5"/>--}}
{{--                                    </svg>--}}
{{--                                </span>--}}
{{--                                {{ $prop->getValue() }}--}}
{{--                            </label>--}}
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <span class="filter__devider"></span>
                @endif
            @endforeach

            <div class="filter__select-menu">
                <div class="filter__select-wrapper filter__select-wrapper--range">

                    <div class="filter__select-header">
                        <h4 class="filter__title main-text main-text--semibold">Ціна, грн</h4>
                    </div>
                    <div class="range">
                        <div class="range__range-select">
                            <div class="range__range-input">
                                <div id="polzunokPrice" data-min="{{ $min }}" data-max="{{ $max }}" data-step="1" class="polzunok polzunokPrice"></div>
                            </div>
                        </div>
                        <div class="range__price-input">
                            <div class="range__price-field">
                                <input type="number"
                                       class="range__price-input-min main-input main-input--width100 main-input--gray polzunok-input-left polzunok-price polzunok-price-min"
                                       name="price[from]"
                                       min="{{ $min }}"
                                       max="{{ $max }}"
                                       value="{{ request('price.from', $min) }}"
                                       step="1"
                                       autocomplete="off"
                                >
                            </div>
                            <div class="range__price-devider">-</div>
                            <div class="range__price-field">
                                <input type="number"
                                       class="range__price-input-max main-input main-input--width100 main-input--gray polzunok-input-left polzunok-price polzunok-price-max"
                                       name="price[to]"
                                       min="{{ $min }}"
                                       max="{{ $max }}"
                                       value="{{ request('price.to', $max) }}"
                                       step="1"
                                       autocomplete="off"
                                >
                            </div>
                            <button class="filter__apply main-text main-text--semibold main-btn main-btn--apply js-btn-okay-filter">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="filter__reset">
        <a href="{{ \FacetFilter::reset(['price']) }}" class="main-btn main-btn--gray js-btn-reset-filter">Скинути фільтри</a>
    </div>
</form>
