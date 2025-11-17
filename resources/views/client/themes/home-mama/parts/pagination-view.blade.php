@php
    if ($paginator->currentPage() > $paginator->lastPage()) {
        abort(404);
    }
@endphp

@if ($paginator->hasPages())
    {{--    Custom default component--}}
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item navigation disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">
                        <svg class="icon-svg icon-svg-right "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use></svg>
                    </span>
                </li>
            @else
                <li class="page-item navigation">
                    <a class="page-link" href="{{ request()->query('page', 2) == 2 ? $paginator->path() : $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                        <svg class="icon-svg icon-svg-right "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use></svg>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @if($paginator->currentPage() > 2)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->path() }}">1</a>
                </li>
            @endif
            @if($paginator->currentPage() > 3)
                {{-- "Three Dots" Separator --}}
                <li class="page-item" aria-disabled="true"><span class="page-link dots">...</span></li>
            @endif
            @foreach(range(1, $paginator->lastPage()) as $i)
                @if($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 1)
                    @if ($i == $paginator->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $i == 1 ? $paginator->path() : $paginator->url($i) }}">{{ $i }}</a>
                        </li>
                    @endif
                @endif
            @endforeach
            @if($paginator->currentPage() < $paginator->lastPage() - 2)
                {{-- "Three Dots" Separator --}}
                <li class="page-item" aria-disabled="true"><span class="page-link dots">...</span></li>
            @endif
            @if($paginator->currentPage() < $paginator->lastPage() - 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item navigation">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        <svg class="icon-svg icon-svg-right "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#right"></use></svg>
                    </a>
                </li>
            @else
                <li class="page-item navigation disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">
                        <svg class="icon-svg icon-svg-right "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#right"></use></svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
