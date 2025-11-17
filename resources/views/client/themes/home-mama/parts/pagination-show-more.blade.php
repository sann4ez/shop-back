@if($hasMore)
    <div class="catalog__pagination js-perpage-wrap-more">
        <button
            class="btn--intern catalog__btn--more js-perpage-btn-more"
            data-url="{{ \Illuminate\Support\Facades\Request::fullUrlWithoutQuery('page') }}"
            data-page="{{ request('page', 1) + 1 }}"
        >+ Показати ще
        </button>
    </div>
@endif
