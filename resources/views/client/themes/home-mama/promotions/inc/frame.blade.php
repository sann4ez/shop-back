<div class="card-promotion">
    <div class="card-promotion__wrapper">
        <a class="card-promotion__link" href="{{ $promotion->getUrlClient() }}">
            <img class="card-promotion__img"
                 src="{{ $promotion->getFirstMediaUrl('image') ?: Theme::url('img/nophoto.webp') }}"
                 alt="promotion-img">
        </a>

        <div class="card-promotion__timer">
            <svg class="icon-svg icon-svg-timer ">
                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#timer"></use>
            </svg>
            @unless($promotion->is_dateless)
            <p class="card-promotion__timer-text">Залишилося
                <span class="card-promotion__timer-duration">
                    {{ $promotion->getTimeRemaining()['days'] }} дн
                    {{ $promotion->getTimeRemaining()['hours'] }} год
                </span>
            </p>
            @else
                {{--<p class="card-promotion__timer-text">Нескінченна</p>--}}
            @endunless
        </div>
        <div class="card-promotion__info">
            <a href="{{ $promotion->getUrlClient() }}" class="link">{{ $promotion->name }}</a>
            <p>{{ $promotion->getDatePeriodStr($isoFormat = 'd F') }}</p>
        </div>
    </div>
</div>
