<div class="sort__order">
    @php($sorts = [
        ['title' => 'Популярні', 'order' => 'desc', 'sort' => 'rating'],
        ['title' => 'Дешевші', 'order' => 'asc', 'sort' => 'price'],
        ['title' => 'Дорожчі', 'order' => 'desc', 'sort' => 'price'],
    ])
    <h4 class="main-text main-text--semibold sort__main-text">Сортування:</h4>
    <ul class="sort__menu">
        @php($sort = array_search_assoc(request()->only('sort', 'order'), $sorts, true))
        @foreach($sorts as $item)
            @if(empty($item['sort']))
                <li class="sort__menu-item">
                    <a href="{{ Request::fullUrlWithoutQuery(['sort','order']) }}" class="sort__menu-link @if($sort['title'] === $item['title']) sort__menu-link--active @endif">{{ $item['title'] }}</a>
                </li>
            @else
                <li class="sort__menu-item">
                    <a href="{{ Request::fullUrlWithQuery(Arr::only($item, ['order', 'sort'])) }}" class="sort__menu-link @if($sort['title'] === $item['title']) sort__menu-link--active @endif">{{ $item['title'] }}</a>
                </li>
            @endif
        @endforeach
    </ul>
</div>

<div class="dropdown sort__order-mobile">
    @php($sort = array_search_assoc(request()->only('sort', 'order'), $sorts, true))
    <button type="button" class="btn btn-secondary dropdown-toggle sort__order-select" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="sort__order-select-name">{{ $sort['title'] }}</span>
        <svg class="sort__order-arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.46973 8.53033L4.53039 7.46967L12.0001 14.9393L19.4697 7.46967L20.5304 8.53033L12.0001 17.0607L3.46973 8.53033Z" fill="black"/>
        </svg>
    </button>
    <ul class="dropdown-menu sort__order-options">
        @foreach($sorts as $item)
            @if(empty($item['sort']))
            <li>
                <a class="dropdown-item sort__order-option" href="{{ Request::fullUrlWithoutQuery(['sort','order']) }}">{{ $item['title'] }}</a>
            </li>
            @else
                <li>
                    <a class="dropdown-item sort__order-option" href="{{ Request::fullUrlWithQuery(Arr::only($item, ['order', 'sort'])) }}">{{ $item['title'] }}</a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
