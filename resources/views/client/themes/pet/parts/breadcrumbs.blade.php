@unless ($breadcrumbs->isEmpty())
    <div class="row">
        <div class="col">
            <ul class="breadcrumb mt-3">
                @foreach ($breadcrumbs as $breadcrumb)

                    @if ($breadcrumb->url && !$loop->last)
                        <li {{--class="breadcrumb-item"--}}><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                    @else
                        <li class="{{--breadcrumb-item--}} active">{{ $breadcrumb->title }}</li>
                    @endif

                @endforeach
            </ul>
        </div>
    </div>
@endunless
