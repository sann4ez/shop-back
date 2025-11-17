<div class="menu__wrapper-category">
    <div class="menu__category-list">
        <ul class="menu__category-list-wrapper menu__category-list-wrapper--desktop">
            @foreach(\App\Models\Term::byVocabulary(\App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES)
                ->with('translations')->get()->toTree() as $term)
                <li class="menu__category-item-link">
                    <a class="menu__category-item" href="{{ $term->getUrlClient() }}">
                        <svg class="icon-svg icon-svg-bust menu__icon"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#{{ $term->getAdded('icon') }}"></use></svg>
                        <span class="menu__category-item-name">{{ $term->name }}</span>
                        @if($term->children->count()) <svg class="icon-svg icon-svg-arrow-right nav__icon menu__icon-side"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg> @endif
                    </a>

                    @if($term->children->count())
                        <div class="menu__category-wrapper">
                            <div class="menu__wrapper-category-bg">
                                <div class="menu__wrapper-category-inner">
                                    @foreach($term->children as $child)
                                        <div class="menu__wrapper-category-inner-wrapper">
                                            <a href="{{ $child->getUrlClient() }}" class="menu__category-title-inner main-text main-text--semibold">
                                                {{ $child->name }}
                                            </a>
                                            @if($child->children->count())
                                                <ul class="meny__category-list-content">
                                                    @foreach($child->children as $c)
                                                        <li class="menu__category-item-inner">
                                                            <a href="{{ $c->getUrlClient() }}" class="menu__category-item-link-inner">{{ $c->name }}</a>
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

        <ul class="menu__category-list-wrapper menu__category-list-wrapper--mobile">
            @foreach(\App\Models\Term::byVocabulary(\App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES)
                ->with('translations')->get()->toTree() as $term)
                <li class="menu__category-item-link ">
                    @if($term->children->count())
                        <p class="menu__category-item header__category--full">
                            <svg class="icon-svg icon-svg-bust menu__icon"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#{{ $term->getAdded('icon') }}"></use></svg>
                            <span class="menu__category-item-name">{{ $term->name }}</span>
                            <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                        </p>
                    @else
                        <a href="{{ $term->getUrlClient() }}" class="menu__category-item header__category--full">
                            <svg class="icon-svg icon-svg-bust menu__icon"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#{{ $term->getAdded('icon') }}"></use></svg>
                            <span class="menu__category-item-name">{{ $term->name }}</span>
                        </a>
                    @endif

                    @if($term->children->count())
                        <div class="menu__category-inner-wrapper js-categories-header">
                            <div class="menu__category-head">
                                <svg class="icon-svg icon-svg-bust menu__icon"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#{{ $term->getAdded('icon') }}"></use></svg>
                                <a class="menu__category-title" >{{ $term->name }}</a>
                                <button
                                    type="button"
                                    class="menu__category-btn-close js-btn-categoris-close"
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

                            <div class="menu__categories ">
                                <div class="menu__category">
                                    @foreach($term->children as $child)
                                        <a href="{{ $child->getUrlClient() }}" class="menu__category-subtitle">{{ $child->name }}</a>
                                        @if($child->children->count())
                                            <ul class="menu__category-list-mobile">
                                                @foreach($child->children as $c)
                                                    <li class="menu__category-item-mobile">
                                                        <a href="{{ $c->getUrlClient() }}" class="menu__category-item-link-mobile">{{ $c->name }}</a>
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
    </div>
</div>
