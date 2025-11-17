<a href="{{ $promotion->getUrlClient() }}" class="card-article card-article--shares">
    <span class="card-article__top">
        <img loading="lazy" src="{{ $promotion->getFirstMediaUrl('image') ?: Theme::url('img/img-error.png') }}" alt="" class="card-article__img">
    </span>
    <span class="card-article__desc">
        <span class="card-article__title main-text">
            {{ $promotion->name }}
        </span>
        <span class="card-article__pubdate main-text main-text--mobile main-text--color-dark-gray">
            @unless($promotion->is_dateless)
                <p class="card-promotion__timer-text">Залишилося
                <span class="card-promotion__timer-duration">
                    {{ $promotion->getTimeRemaining()['days'] }} дн
                    {{ $promotion->getTimeRemaining()['hours'] }} год
                </span>
            </p>
            @else
                <p class="card-promotion__timer-text">Нескінченна</p>
            @endunless
        </span>
    </span>
</a>
