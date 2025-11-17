@unless ($breadcrumbs->isEmpty())
<nav class="header-page__breadcrumbs">
    <ul class="breadcrumbs">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->url && !$loop->last)
                <li class="breadcrumbs__item main-text main-text--caption main-text--caption-mobile">
                    <a class="breadcrumbs__item-link" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                </li>
            @else
                <li class="breadcrumbs__item main-text main-text--caption main-text--caption-mobile">
                    <p class="breadcrumbs__item-link">{{ $breadcrumb->title }}</p>
                </li>
            @endif
        @endforeach
    </ul>
</nav>
@endunless
