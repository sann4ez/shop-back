@if ($paginator->hasPages())
    {{--    Custom default component--}}
    <ul class="pagination pagination--catalog pagination__bullets-wrapper">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span class="page-link arrow arrow__left arrow--pagination arrow__pagination--left arrow__left--locked" aria-hidden="true">
                    <svg class="icon-svg icon-svg-arrow-category color-red arrow-category"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-category"></use></svg>
                </span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link arrow arrow__left arrow--pagination arrow__pagination--left" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                    <svg class="icon-svg icon-svg-arrow-category color-red arrow-category"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-category"></use></svg>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @if($paginator->currentPage() > 2)
            <li class="page-item pagination__bullet">
                <a class="page-link page-link--nullifier main-text" href="{{ $paginator->url(1) }}">1</a>
            </li>
        @endif
        @if($paginator->currentPage() > 3)
            {{-- "Three Dots" Separator --}}
            <li class="page-item paginationbullet paginationbullet--dots">
                <p class="page-link page-link--nullifier main-text" href="#">
                    ...
                </p>
            </li>
        @endif
        @foreach(range(1, $paginator->lastPage()) as $i)
            @if($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 1)
                @if ($i == $paginator->currentPage())
                    <li class="page-item pagination__bullet pagination__bullet--active" aria-current="page">
                        <span class="page-link page-link--nullifier main-text">{{ $i }}</span>
                    </li>
                @else
                    <li class="page-item pagination__bullet">
                        <a class="page-link page-link--nullifier main-text" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endif
            @endif
        @endforeach
        @if($paginator->currentPage() < $paginator->lastPage() - 2)
            {{-- "Three Dots" Separator --}}
            <li class="page-item paginationbullet paginationbullet--dots">
                <p class="page-link page-link--nullifier main-text" href="#">
                    ...
                </p>
            </li>
        @endif
        @if($paginator->currentPage() < $paginator->lastPage() - 1)
            <li class="page-item pagination__bullet">
                <a class="page-link page-link--nullifier main-text" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
            </li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link arrow arrow__right arrow--pagination arrow__pagination--right" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                    <svg class="icon-svg icon-svg-arrow-category color-red arrow-category"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-category"></use></svg>
                </a>
            </li>
        @else
            <li class="page-item" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="page-link arrow arrow__right arrow--pagination arrow__pagination--right arrow__right--locked" aria-hidden="true">
                    <svg class="icon-svg icon-svg-arrow-category color-red arrow-category"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-category"></use></svg>
                </span>
            </li>
        @endif
    </ul>
@endif
