@foreach($comments as $comment)
    <li class="product__reviews-item">
        <div class="product__reviews-name">{{ $comment->getAuthorName() }}</div>

        <div class="product-rating rating" data-rating="{{ $comment->getRating() }}"></div>
        <p class="product__reviews-text">{{ $comment->body }}</p>
    </li>
@endforeach
