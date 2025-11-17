@php($max = $max ?? 5)
@for($i = 1; $i <= $max; $i++)
    @if($rating >= $i)
        <i class="fas fa-star text-color-dark mr-1"></i>
    @else
        <i class="fas fa-star text-color-light-2 mr-1"></i>
    @endif
@endfor