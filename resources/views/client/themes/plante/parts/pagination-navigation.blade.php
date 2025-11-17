@if ($items->hasPages())
    <nav aria-label="..." class="articles__pagination js-perpage-wrap-more">
    @if($hasMore)
    <div class="pagination__more">
        <button class="main-btn main-btn--second-green js-perpage-btn-more"
            data-url="{{ \Illuminate\Support\Facades\Request::fullUrlWithoutQuery('page') }}"
            data-page="{{ request('page', 1) + 1 }}"
            aria-label="showMore">
            Показати ще +
        </button>
    </div>
    @endif

    @include('parts.pagination', ['items' => $items])
    </nav>
@endif
{{--@if ($items->hasPages())--}}
{{--    <div class="js-perpage-wrap-more">--}}
{{--        @if($hasMore)--}}
{{--            <div class="catalog__pagination">--}}
{{--                <button--}}
{{--                    class="btn--intern catalog__btn--more js-perpage-btn-more"--}}
{{--                    data-url="{{ \Illuminate\Support\Facades\Request::fullUrlWithoutQuery('page') }}"--}}
{{--                    data-page="{{ request('page', 1) + 1 }}"--}}
{{--                >+ Показати ще--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        @endif--}}

{{--        @include('parts.pagination', ['items' => $items])--}}
{{--    </div>--}}
{{--@endif--}}
