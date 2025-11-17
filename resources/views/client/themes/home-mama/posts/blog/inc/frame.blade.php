<a href="{{ $post->getUrlClient() }}" class="articles-item">
    <span class="articles-item__wrapper">
        <span class="articles-item__link" >
            <span class="articles-item__img-wrapper">
                <img class="articles-item__img"
                     src="{{ Theme::url($post->getFirstMediaUrl('image', 'preview') ?: Theme::url('img/nophoto.webp')) }}" width="360" height="200"
                     onerror="this.onerror=null;this.src='img/nophoto.webp';" alt="article-card">
            </span>

        </span>
        <span class="articles-item__content">
            <span class="articles-item__date text text-gray">
                {{ $post->getAuthor() ? $post->getDatetime('created_at', 'd F Y') . ' | ' . $post->getAuthor() : $post->getDatetime('created_at', 'd F Y') }}
            </span>
            <span class="articles-item__link" href="/article.html">
                <span class="articles-item__title text">
                    {{ $post->name }}
                </span>
            </span>
        </span>
    </span>
</a>
