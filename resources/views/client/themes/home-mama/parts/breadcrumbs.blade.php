@unless ($breadcrumbs->isEmpty())
    <nav class="breadcrumbs">
        <ul class="breadcrumbs-list">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($breadcrumb->url && !$loop->last)
                    <li class="breadcrumbs-item"><a href="{{ $breadcrumb->url }}"
                                                    class="breadcrumbs-link">{{ $breadcrumb->title }} ></a></li>
                @else
                    <li class="breadcrumbs-item"><a href="{{ $breadcrumb->url }}" class="breadcrumbs-link">{{ $breadcrumb->title }}</a></li>
                @endif
            @endforeach
        </ul>
    </nav>
@endunless
