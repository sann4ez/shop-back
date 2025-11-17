@forelse($promotions as $promotion)
    @if($promotion->isAllowedForUser())
        @include('promotions.inc.frame')
    @endif
@empty

@endforelse
