<div class="product__reviews-top">
    @if($variation->product->comments->count())
        <div class="product__reviews-average">
            Середня оцінка: <span>{{ $variation->getRating() }}</span
            >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 20 20"
            >
                <path
                    d="M10 0L12.3607 7.25735H20L13.8197 11.7426L16.1803 19L10 14.5147L3.81966 19L6.18034 11.7426L0 7.25735H7.63932L10 0Z"
                />
            </svg>
        </div>
    @endif
    <button class="btn--intern product__reviews-btn" data-bs-toggle="modal" @auth data-bs-target="#commentModal" @else data-bs-target="#loginModal" @endauth>
        Залишити відгук
        <svg
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M5 14.659V18H8.34713M5 14.659L15.6529 4L19 7.34102L8.34713 18M5 14.659L8.34713 18M8.34713 18H17.5517"
                stroke="white"
                stroke-width="1.4"
            />
        </svg>
    </button>
</div>
@if($variation->product->comments->count())
    <ul class="product__reviews-list js-perpage-source">
        @include('catalog.inc.comments-list')
    </ul>
        @include('parts.pagination-show-more', ['items' => $comments])
@endif

@push('modals')

    <!-- Modal Comment -->

    <div class="modal modal-default modal-comment fade" id="commentModal" aria-labelledby="commentModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-body__head">
                        <div class="title">Додати відгук</div>
                        <button class="btn--close" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <svg class="icon-svg icon-svg-exit ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>
                            </svg>
                        </button>
                    </div>

                    @auth
                    <form class="product__reviews-form"
                          action="{{ route('catalog.product.comment', $variation->product) }}"
                          method="POST" >
                        @csrf
                        @honeypot
                        <div class="product__reviews--write">
                            <span class="product__reviews-mark">Ваша оцінка</span>
                            <div class="set-rating"></div>
                            <input
                                class="rating-input"
                                type="number"
                                min="0"
                                max="5"
                                value="5"
                                name="rating"
                                style="display: none"
                            >
                        </div>
                        <div class="textarea__wrapper @error('body') error @enderror">
                            <textarea class="textarea" name="body" placeholder="Коментар"></textarea>
                            @error('rating') <p>{{ $message }}</p> @enderror
                            @error('body') <p>{{ $message }}</p> @enderror
                        </div>
                        <div class="product__reviews-bottom">
                            <button type="submit" class="btn--intern">Залишити відгук</button>
                        </div>
                    </form>
                    @else
                        <div class="product__reviews-bottom">
                            <a class="btn--intern">Увійти в особистий кабінет</a>
                            <p>Для того щоб опублікувати відгук необхідно увійти в особистий кабінет</p>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </div>

@endpush
