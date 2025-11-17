@if($block = Block::init('reviewed_variations'))
    @if($block->getData('reviewed_variations')->count())
        <div class="sale" aria-label="sliderGoods">
            <!-- <div class="container"> -->
            <div class="sale__content">
                <div class="sale__wrapper">
                    <div class="sale__header">
                        <div class="sale__descr">
                            <h2 class="title">{{ $block->getContent('title') }}</h2>
                        </div>
                        <div class="arrow">
                            <button class="sale__arrow--left arrow__left" aria-label="previousSlide">
                                <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                            </button>
                            <button class="sale__arrow--right arrow__right" aria-label="nextSlide">
                                <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="sale__products swiper-container" id="sale-slider-1">

                    <div class="swiper-wrapper">

                        @foreach($block->getData('reviewed_variations') as $item)
                            @include('catalog.inc.variation-frame', ['variation' => $item])
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
