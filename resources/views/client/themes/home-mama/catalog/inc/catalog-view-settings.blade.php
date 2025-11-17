@php($sorts = [
    ['title' => 'По замовчуванню', 'sort' => 'default', 'order' => 'desc'],
    ['title' => 'Нові', 'order' => 'desc', 'sort' => 'income_at'],
    ['title' => 'Популярні', 'order' => 'desc', 'sort' => 'rating'],
    ['title' => 'Дешевші', 'order' => 'asc', 'sort' => 'price'],
    ['title' => 'Дорожчі', 'order' => 'desc', 'sort' => 'price'],
])
<button class="btn--intern catalog__btn-filter" type="button" data-bs-toggle="modal"
        data-bs-target="#filterModal">Фільтр
    <svg class="icon-svg icon-svg-filter "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#filter"></use></svg>
</button>
<div class="catalog__wrapper-links">
    @php($sort = array_search_assoc(request()->only('sort', 'order'), $sorts, true))
        @foreach($sorts as $item)
            @if(empty($item['sort']))
                <a href="{{ urldecode(Request::fullUrlWithoutQuery(['sort','order'])) }}" class="catalog__wrapper-link link
                @if($sort['title'] === $item['title']) active @endif">{{ $item['title'] }}</a>
            @else
                <a href="{{ urldecode(Request::fullUrlWithQuery(Arr::only($item, ['order', 'sort']))) }}" class="catalog__wrapper-link link
                @if($sort['title'] === $item['title']) active @endif">{{ $item['title'] }}</a>
            @endif
        @endforeach

</div>
<div class="catalog__wrapper-btn">
    <a href="#"
       class="catalog-btn {{ request()->cookie('view', 'card') === 'card' ? 'active' : '' }}"
       data-view="card">
        <svg
            width="24"
            height="24"
            viewBox="0 0 30 30"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <rect width="24" height="24" rx="5" fill="white"/>
            <circle cx="10.5" cy="10.5" r="2.5"/>
            <circle cx="10.5" cy="19.5" r="2.5"/>
            <circle cx="19.5" cy="10.5" r="2.5"/>
            <circle cx="19.5" cy="19.5" r="2.5"/>
        </svg>
    </a>

    <a href="#"
       class="catalog-btn {{ request()->cookie('view') === 'list' ? 'active' : '' }}"
       data-view="list">
        <svg
            width="24"
            height="24"
            viewBox="0 0 30 30"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <rect width="24" height="24" rx="5" fill="white"/>
            <circle cx="8" cy="8" r="2"/>
            <circle cx="8" cy="15" r="2"/>
            <circle cx="8" cy="22" r="2"/>
            <circle cx="15" cy="8" r="2"/>
            <circle cx="15" cy="15" r="2"/>
            <circle cx="15" cy="22" r="2"/>
            <circle cx="22" cy="8" r="2"/>
            <circle cx="22" cy="15" r="2"/>
            <circle cx="22" cy="22" r="2"/>
        </svg>
    </a>
</div>
<div class="select">
    <select class="js-select js-select-change">
        @foreach($sorts as $item)
        <option
            @if($sort['title'] === $item['title']) selected @endif
            value="{{ urldecode(Request::fullUrlWithQuery(Arr::only($item, ['order', 'sort']))) }}">
            {{ $item['title'] }}
        </option>
        @endforeach
    </select>
</div>

@push('scripts')
    <script>
        document.querySelectorAll('.catalog__wrapper-btn .catalog-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const view = this.dataset.view;

                fetch("{{ route('my.profile.options') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        key: "view",
                        value: view
                    })
                }).then(() => {
                    window.location.reload();
                });
            });
        });
    </script>

@endpush
