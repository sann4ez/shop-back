@foreach($promotions as $promotion)
    {{--@if($promotion->isAllowedForUser())--}}
        @include('promotions.inc.frame')
    {{--@endif--}}
@endforeach
