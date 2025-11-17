<div class="card-article">
    <a href="{{ $post->getUrlClient() }}" class="card-article__img-wrap">
        <img loading="lazy" src="{{ $post->getFirstMediaUrl('image', 'preview') ?: Theme::url('img/img-error.png') }}" alt="{{ $post->name }}" class="card-article__img">
    </a>
    <div class="card-article__desc">
        <a href="{{ $post->getUrlClient() }}" class="card-article__title main-text">
            {{ $post->name }}
        </a>
        <div class="card-article__pubdate main-text main-text--mobile main-text--color-dark-gray">{{ $post->getDatetime('created_at', 'd F Y') }}</div>
    </div>
</div>
