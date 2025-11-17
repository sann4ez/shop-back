@php
    $min = $facet['prices']['category']['from'] ?? 0;
    $max = ($facet['prices']['category']['to'] ?? 10000) == 0 ? $facet['prices']['max'] ?? 10000 : $facet['prices']['category']['to'] ?? 10000;
@endphp
<div class="filter">
    <form action="{{ urldecode(\Request::url()) }}" method="get">
        <input type="hidden" name="q" value="{{ request('q') ?: null }}">
        <input type="hidden" name="{{ config('laravel-url-facet-filter.url_keys.filter') }}" value="{{ request(config('laravel-url-facet-filter.url_keys.filter')) ?: null }}">
        <div class="accordion" id="accordionPanelsStayOpenExampleModal">
            @if(request(config('laravel-url-facet-filter.url_keys.filter')) || request('price'))
            <div class="accordion-item">
                <div class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseChips" aria-expanded="true" aria-controls="panelsStayOpen-collapseChips">
                        Ви вибрали <svg class="icon-svg icon-svg-top "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use></svg>
                    </button>
                </div>
                <div id="panelsStayOpen-collapseChips" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <div class="filter-chips">
                            @foreach ($facet2 as $slug => $values)
                                @php
                                    if ($slug === 'brands') {
                                        $label = ucfirst($slug);

                                        $facetProperties = collect($facet['brands']->resolve());
                                    } else {
                                        $facetItem = collect($facet['attributes']->resolve())->firstWhere('slug', $slug);

                                        $label = $facetItem['name'] ?? ucfirst($slug);

                                        $facetProperties = isset($facetItem['properties'])
                                            ? $facetItem['properties']->resolve()
                                            : collect();
                                    }
                                @endphp

                                <div class="filter-chips__wrapper">
                                    <div class="filter-chips__name">
                                        {{ $label }}:
                                    </div>

                                    @foreach ($values as $value)
                                        @php
                                            $property = collect($facetProperties)->firstWhere('slug', $value);

                                            $name = $property['value'] ?? $property['name'] ?? $value;
                                        @endphp

                                        <a href="{{ \FacetFilter::build($slug, $value) }}"
                                           class="filter-chips__value">
                                            {{ $name }}
                                            <svg class="icon-svg icon-svg-close-chips close-chips">
                                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#close-chips"></use>
                                            </svg>
                                        </a>

                                    @endforeach
                                </div>

                            @endforeach

                            @if(request('price'))
                                <div class="filter-chips__wrapper">
                                    <div class="filter-chips__name">
                                        Ціна:
                                    </div>
                                    @php
                                        $currentUrl = request()->fullUrl();

                                        $urlParts = parse_url($currentUrl);
                                        $queryParams = [];

                                        if(isset($urlParts['query'])) {
                                            parse_str($urlParts['query'], $queryParams);
                                            unset($queryParams['price']);
                                        }

                                        $newQuery = !empty($queryParams) ? http_build_query($queryParams) : null;

                                        $newUrl = urldecode($urlParts['path'] . '?' . ($newQuery ? $newQuery : ''));
                                    @endphp
                                    <a href="{{ $newUrl }}" class="filter-chips__value">
                                        {{ request('price.from') }} - {{ request('price.to') }} грн
                                        <svg class="icon-svg icon-svg-close-chips close-chips"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#close-chips"></use></svg>
                                    </a>
                                </div>
                            @endif
                            <a href="{{ \Request::url() . '?q=' . request('q') }}" class="filter-chips__reset"><svg class="icon-svg icon-svg-delete-chips delete-chips"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#delete-chips"></use></svg> Очистити фільтри</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashed-line"></div>
            @endif

            @if(isset($facet['brands']) && count($facet['brands']))
                <div class="accordion-item">
                    <div class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                aria-controls="panelsStayOpen-collapseOne">
                            Бренди
                            <svg class="icon-svg icon-svg-top ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                            </svg>
                        </button>
                    </div>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <ul class="filter-list">
                            @foreach($facet['brands'] as $brand)
                                @if($brand->is_allowed)
                                <li class="filter-item">
                                    <label class="checkbox-label js-click-url"
                                           data-url="{{ \FacetFilter::build('brands', $brand->slug) }}">
                                        <input type="checkbox"
                                               @if(\FacetFilter::has('brands', $brand->slug)) checked @endif
                                               id="prop-{{$brand->id}}">
                                        <span class="checkmark"></span>

                                        {{ $brand->name }}
                                    </label>
                                </li>
                                @endif
                            @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="dashed-line"></div>
            @endif

            @foreach($facet['attributes'] ?? [] as $attr)
                @if($attr->is_allowed)
                <div class="accordion-item">
                    <div class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapse{{ $attr->id }}Modal" aria-expanded="true"
                                aria-controls="panelsStayOpen-collapse{{ $attr->id }}Modal">
                            {{ $attr->getName() }}
                            <svg class="icon-svg icon-svg-top ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                            </svg>
                        </button>
                    </div>
                    <div id="panelsStayOpen-collapse{{ $attr->id }}Modal" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <ul class="filter-list">
                            @foreach($attr->properties as $prop)
                                @if($prop->is_allowed)
                                    <li class="filter-item">
                                        <label class="checkbox-label js-click-url"
                                               data-url="{{ \FacetFilter::build($attr->slug, $prop->slug) }}">
                                            <input type="checkbox"
                                                   @if(\FacetFilter::has($attr->slug, $prop->slug)) checked @endif
                                                   id="prop-{{$prop->id}}">
                                            <span class="checkmark"></span>
                                            {{ $prop->getValue() }}
                                        </label>
                                    </li>
                                @endif
                            @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="dashed-line"></div>
                @endif
            @endforeach
            <div class="accordion-item">
                <div class="accordion-header">
                    <button
                        class="accordion-button"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#panelsStayOpen-collapseThree"
                        aria-expanded="true"
                        aria-controls="panelsStayOpen-collapseThree"
                    >
                        Ціна
                        <svg class="icon-svg icon-svg-top ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                        </svg>
                    </button>
                </div>
                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <div class="accordion-body__wrapper">
                            <div
                                id="polzunokPrice"
                                data-min="{{ $min }}"
                                data-max="{{ $max }}"
                                data-step="1"
                                class="polzunok polzunokPrice"
                            ></div>
                            <div class="filter-inputs">
                                <input
                                    type="text"
                                    name="price[from]"
                                    value="{{ request('price.from', $min) }}"
                                    class="polzunok-input-left polzunok-price polzunok-price-min"
                                />
                                <span class="default-text">-</span>
                                <input
                                    type="text"
                                    name="price[to]"
                                    value="{{ request('price.to', $max) }}"
                                    class="polzunok-input-left polzunok-price polzunok-price-max"
                                />
                                <button type="submit" class="btn--extern">ОК</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashed-line"></div>
        </div>
    </form>
    <a href="{{ \FacetFilter::reset(['price']) }}" class="filter-reset link">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
             xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" fill="#E25566"/>
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M13.688 8.38386C12.8603 7.9396 11.9035 7.79531 10.9809 7.97572C10.0583 8.15614 9.22735 8.65001 8.62937 9.37274C8.03143 10.0954 7.70339 11.0023 7.7008 11.9388C7.6982 12.8753 8.02121 13.784 8.61514 14.51C9.2091 15.236 10.0373 15.7344 10.9589 15.9199C11.8805 16.1054 12.838 15.9664 13.6682 15.5267C14.4983 15.087 15.1496 14.3741 15.5113 13.5096C15.6606 13.153 16.0707 12.9849 16.4273 13.1341C16.7839 13.2834 16.952 13.6935 16.8028 14.0501C16.3157 15.2139 15.4394 16.1728 14.3235 16.7639C13.2076 17.3549 11.9209 17.5416 10.6827 17.2924C9.44439 17.0431 8.33071 16.3733 7.53157 15.3965C6.73239 14.4196 6.29731 13.1963 6.3008 11.9349C6.30429 10.6736 6.74614 9.4527 7.55071 8.48028C8.35524 7.50789 9.47261 6.84416 10.7122 6.60175C11.9519 6.35934 13.2374 6.55314 14.3501 7.1503C14.9597 7.47751 15.4968 7.91477 15.9369 8.43513L16.3288 7.09257C16.4372 6.72146 16.8258 6.50845 17.1969 6.61678C17.5681 6.72512 17.7811 7.11379 17.6727 7.4849L16.8391 10.3405C16.7367 10.6911 16.3821 10.9041 16.0246 10.8297L13.2868 10.2603C12.9083 10.1816 12.6653 9.81097 12.744 9.43247C12.8227 9.05397 13.1934 8.81094 13.5719 8.88966L14.6655 9.11711C14.3808 8.82701 14.0518 8.57913 13.688 8.38386Z"
                  fill="white"/>
        </svg>
        Скинути всі фільтри</a>
</div>

<!-- Modal Filter -->

<div class="modal modal-default fade modal-filter" id="filterModal" aria-labelledby="filterModalLabel">
    <div class="modal-dialog  ">
        <div class="modal-content modal-cart">
            <div class="modal-body">
                <div class="modal-body__head">
                    <div class="title">Фільтр</div>
                    <button class="btn--close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <svg class="icon-svg icon-svg-exit ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>
                        </svg>
                    </button>
                </div>
                <div class="filter">
                    <div class="accordion" id="accordionPanelsStayOpenExampleModal">
                        <form action="{{ \Request::url() }}" method="get">
                        <input type="hidden" name="q" value="{{ request('q') ?: null }}">
                        <input type="hidden" name="config('laravel-url-facet-filter.url_keys.filter')" value="{{ request(config('laravel-url-facet-filter.url_keys.filter')) ?: null }}">

                        @if(request(config('laravel-url-facet-filter.url_keys.filter')) || request('price'))
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapseChips" aria-expanded="true" aria-controls="panelsStayOpen-collapseChips">
                                        Ви вибрали <svg class="icon-svg icon-svg-top "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use></svg>
                                    </button>
                                </div>
                                <div id="panelsStayOpen-collapseChips" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <div class="filter-chips">
                                        @foreach ($facet2 as $slug => $values)
                                            @php
                                                // Визначаємо label і властивості атрибута
                                                if ($slug === 'brands') {
                                                    $label = 'Бренди';
                                                    $facetProperties = collect($facet['brands']->resolve());
                                                    $model = \App\Models\Term::class;
                                                } else {
                                                    $facetItem = collect($facet['attributes']->resolve())->firstWhere('slug', $slug);
                                                    $label = $facetItem['name'] ?? ucfirst($slug);
                                                    $facetProperties = isset($facetItem['properties']) ? collect($facetItem['properties']->resolve()) : collect();
                                                    $model = \App\Models\Eav\Attribute::class;
                                                }
                                            @endphp

                                            <div class="filter-chips__wrapper">
                                                <div class="filter-chips__name">
                                                    {{ $label }}:
                                                </div>

                                                {{-- Відображаємо вибрані значення --}}
                                                @foreach ($values as $value)
                                                    @php
                                                        $property = $facetProperties->firstWhere('slug', $value);
                                                        $name = $property['value'] ?? $property['name'] ?? $value;

                                                        // Для брендів шукаємо модель у термінах
                                                        if ($model === \App\Models\Term::class) {
                                                            $term = $model::where('slug', $value)->first();
                                                            $name = $term ? $term->name : $name;
                                                        }
                                                    @endphp

                                                    <a href="{{ \FacetFilter::build($slug, $value) }}" class="filter-chips__value">
                                                        {{ $name }}
                                                        <svg class="icon-svg icon-svg-close-chips close-chips">
                                                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#close-chips"></use>
                                                        </svg>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endforeach

                                        @if(request('price'))
                                                <div class="filter-chips__wrapper">
                                                    <div class="filter-chips__name">
                                                        Ціна:
                                                    </div>
                                                    @php
                                                        $currentUrl = request()->fullUrl();

                                                        $urlParts = parse_url($currentUrl);
                                                        $queryParams = [];

                                                        if(isset($urlParts['query'])) {
                                                            parse_str($urlParts['query'], $queryParams);
                                                            unset($queryParams['price']);
                                                        }

                                                        $newQuery = !empty($queryParams) ? http_build_query($queryParams) : null;

                                                        $newUrl = urldecode($urlParts['path'] . '?' . ($newQuery ? $newQuery : ''));
                                                    @endphp
                                                    <a href="{{ $newUrl }}" class="filter-chips__value">
                                                        {{ request('price.from') }} - {{ request('price.to') }} грн
                                                        <svg class="icon-svg icon-svg-close-chips close-chips"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#close-chips"></use></svg>
                                                    </a>
                                                </div>
                                            @endif
                                            <a href="{{ \Request::url() . '?q=' . request('q') }}" class="filter-chips__reset"><svg class="icon-svg icon-svg-delete-chips delete-chips"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#delete-chips"></use></svg> Очистити фільтри</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dashed-line"></div>
                        @endif

                        @if(isset($facet['brands']) && count($facet['brands']))
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapseOneModal" aria-expanded="true"
                                            aria-controls="panelsStayOpen-collapseOneModal">
                                        Бренди
                                        <svg class="icon-svg icon-svg-top ">
                                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                                        </svg>
                                    </button>
                                </div>
                                <div id="panelsStayOpen-collapseOneModal" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <ul class="filter-list">
                                        @foreach($facet['brands'] as $brand)
                                            @if($brand->is_allowed)
                                            <li class="filter-item js-click-url"
                                                data-url="{{ \FacetFilter::build('brands', $brand->slug) }}">
                                                <label class="checkbox-label">
                                                    <input type="checkbox"
                                                           @if(\FacetFilter::has('brands', $brand->slug)) checked @endif
                                                           id="prop-{{$brand->id}}">
                                                    <span class="checkmark"></span>

                                                    {{ $brand->name }}
                                                </label>
                                            </li>
                                            @endif
                                        @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="dashed-line"></div>
                        @endif
                        @if(isset($facet['attributes']) && count($facet['attributes']))
                            @foreach($facet['attributes'] as $attr)
                                @if($attr->is_allowed)
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapse{{ $attr->id }}Modal"
                                                aria-expanded="true"
                                                aria-controls="panelsStayOpen-collapse{{ $attr->id }}Modal">
                                            {{ $attr->getName() }}
                                            <svg class="icon-svg icon-svg-top ">
                                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                                            </svg>
                                        </button>
                                    </div>
                                    <div id="panelsStayOpen-collapse{{ $attr->id }}Modal"
                                         class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <ul class="filter-list">
                                            @foreach($attr->properties as $prop)
                                                @if($prop->is_allowed)
                                                    <li class="filter-item">
                                                        <label class="checkbox-label  js-click-url"
                                                               data-url="{{ \FacetFilter::build($attr->slug, $prop->slug) }}">
                                                            <input type="checkbox"
                                                                   @if(\FacetFilter::has($attr->slug, $prop->slug)) checked
                                                                   @endif
                                                                   id="prop-{{$prop->id}}">
                                                            <span class="checkmark"></span>

                                                            {{ $prop->getValue() }}
                                                        </label>
                                                    </li>
{{--                                                @else--}}
{{--                                                    <li class="filter-item">--}}
{{--                                                        <label class="checkbox-label disabled">--}}
{{--                                                            <input type="checkbox"--}}
{{--                                                                   id="prop-{{$prop->id}}"--}}
{{--                                                                   disabled>--}}
{{--                                                            <span class="checkmark"></span>--}}

{{--                                                            {{ $prop->getValue() }}--}}
{{--                                                        </label>--}}
{{--                                                    </li>--}}
                                                @endif
                                            @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="dashed-line"></div>
                                @endif
                            @endforeach
                        @endif
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseThreeModal" aria-expanded="true"
                                        aria-controls="panelsStayOpen-collapseThreeModal">
                                    Ціна
                                    <svg class="icon-svg icon-svg-top ">
                                        <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                                    </svg>
                                </button>
                            </div>
                            <div id="panelsStayOpen-collapseThreeModal"
                                 class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <div class="accordion-body__wrapper">
                                        <div id="polzunokPrice2"
                                             data-min="{{ $min }}"
                                             data-max="{{ $max }}"
                                             data-step="1"
                                             class="polzunok polzunokPrice">
                                        </div>
                                        <div class="filter-inputs">
                                            <input type="text"
                                                   name="price[from]"
                                                   value="{{ request('price.from', $min) }}"
                                                   class="polzunok-input-left polzunok-price2 polzunok-price-min2">
                                            <span class="default-text">-</span>
                                            <input type="text"
                                                   name="price[to]"
                                                   value="{{ request('price.to', $max) }}"
                                                   class="polzunok-input-left polzunok-price2  polzunok-price-max2">
                                            <button class="btn--extern">ОК</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dashed-line"></div>
                        </form>
                    </div>
                    <button class="btn--extern modal-btn--bottom" data-bs-dismiss="modal">Назад</button>
                    {{--                        <button type="submit" class="btn--intern modal-btn--bottom">Застосувати</button>--}}
                    <a href="{{ \FacetFilter::reset(['price']) }}" class="filter-reset link">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="#E25566"/>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M13.688 8.38386C12.8603 7.9396 11.9035 7.79531 10.9809 7.97572C10.0583 8.15614 9.22735 8.65001 8.62937 9.37274C8.03143 10.0954 7.70339 11.0023 7.7008 11.9388C7.6982 12.8753 8.02121 13.784 8.61514 14.51C9.2091 15.236 10.0373 15.7344 10.9589 15.9199C11.8805 16.1054 12.838 15.9664 13.6682 15.5267C14.4983 15.087 15.1496 14.3741 15.5113 13.5096C15.6606 13.153 16.0707 12.9849 16.4273 13.1341C16.7839 13.2834 16.952 13.6935 16.8028 14.0501C16.3157 15.2139 15.4394 16.1728 14.3235 16.7639C13.2076 17.3549 11.9209 17.5416 10.6827 17.2924C9.44439 17.0431 8.33071 16.3733 7.53157 15.3965C6.73239 14.4196 6.29731 13.1963 6.3008 11.9349C6.30429 10.6736 6.74614 9.4527 7.55071 8.48028C8.35524 7.50789 9.47261 6.84416 10.7122 6.60175C11.9519 6.35934 13.2374 6.55314 14.3501 7.1503C14.9597 7.47751 15.4968 7.91477 15.9369 8.43513L16.3288 7.09257C16.4372 6.72146 16.8258 6.50845 17.1969 6.61678C17.5681 6.72512 17.7811 7.11379 17.6727 7.4849L16.8391 10.3405C16.7367 10.6911 16.3821 10.9041 16.0246 10.8297L13.2868 10.2603C12.9083 10.1816 12.6653 9.81097 12.744 9.43247C12.8227 9.05397 13.1934 8.81094 13.5719 8.88966L14.6655 9.11711C14.3808 8.82701 14.0518 8.57913 13.688 8.38386Z"
                                  fill="white"/>
                        </svg>
                        Скинути всі фільтри</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Фіксована кнопка фільтру --}}
<button class="btn--intern catalog__btn-filter catalog__btn-filter-fixed" type="button" data-bs-toggle="modal"
        data-bs-target="#filterModal">
    <svg class="icon-svg icon-svg-filter "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#filter"></use></svg>
</button>
