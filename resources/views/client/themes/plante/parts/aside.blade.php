<ul class="home__promo-category-list-wrapper home__promo-category-list-wrapper--desktop" aria-label="categoryList">
    @foreach(\App\Models\Term::byVocabulary(\App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES)
        ->with('translations')->get()->toTree() as $term)
    <li class="home__promo-category-item-link" aria-label="categoryLink">
        <a class="home__promo-category-item" href="{{ $term->getUrlClient() }}">
            <span class="material-symbols-outlined">{{ $term->getAdded('icon') }}</span>
            <span class="home__promo-category-item-name">{{ $term->name }}</span>
            @if($term->children->count()) <svg class="icon-svg icon-svg-arrow-category color-red arrow-category"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-category"></use></svg> @endif
        </a>
        @if($term->children->count())
        <div class="home__promo-category-wrapper" >
            <div class="home__promo-category-bg">
                <div class="home__promo-category-inner-content">
                @foreach($term->children as $child)
                <div class="home__promo-category-inner-wrapper">
                    <a href="{{ $child->getUrlClient() }}" class="home__promo-category-title-inner main-text main-text--semibold">
                        {{ $child->name }}
                    </a>
                    @if($child->children->count())
                    <ul class="home__promo-category-list-content" aria-label="innerList">
                        @foreach($child->children as $c)
                        <li class="home__promo-category-item-inner">
                            <a href="{{ $c->getUrlClient() }}" class="home__promo-category-item-link-inner">
                                {{ $c->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endforeach
            </div>
            </div>
        </div>
        @endif
    </li>
    @endforeach
</ul>

<ul class="home__promo-category-list-wrapper home__promo-category-list-wrapper--mobile">
    @foreach(\App\Models\Term::byVocabulary(\App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES)
        ->with('translations')->get()->toTree() as $term)
    <li class="home__promo-category-item-link ">
        @if($term->children->count())
            <p class="home__promo-category-item header__category--full">
                <span class="material-symbols-outlined">{{ $term->getAdded('icon') }}</span>
                <span class="home__promo-category-item-name">{{ $term->name }}</span>
                <svg class="icon-svg icon-svg-arrow-category color-red arrow-category"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-category"></use></svg>
            </p>
        @else
            <a href="{{ $term->getUrlClient() }}" class="home__promo-category-item header__category--full">
                <span class="material-symbols-outlined">{{ $term->getAdded('icon') }}</span>
                {{ $term->name }}
            </a>
        @endif

        @if($term->children->count())
        <div class="header__category-inner-wrapper js-categories-header">
            <div class="header__category-head">
                <span class="title header__category-title" >{{ $term->name }}</span>
                <button
                    type="button"
                    class="header__category-btn-close js-btn-categoris-close"
                >
                    <svg
                        class="icon-close"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 16 16"
                        fill="none"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M9.41804 8.00001L15.2109 2.20712L13.7967 0.792908L8.00383 6.5858L2.21094 0.792908L0.796724 2.20712L6.58962 8.00002L0.796724 13.7929L2.21094 15.2071L8.00383 9.41423L13.7967 15.2071L15.2109 13.7929L9.41804 8.00001Z"
                            fill="black"
                        />
                    </svg>
                </button>
            </div>

            <div class="header__categories ">
                <div class="header__category">
                    @foreach($term->children as $child)
                    <a href="{{ $child->getUrlClient() }}" class="header__category-subtitle">{{ $child->name }}</a>
                        @if($child->children->count())
                        <ul class="home__promo-category-list-mobile">
                            @foreach($child->children as $c)
                            <li class="home__promo-category-item-mobile">
                                <a href="{{ $c->getUrlClient() }}" class="home__promo-category-item-link-mobile">{{ $c->name }}</a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </li>
    @endforeach
</ul>
