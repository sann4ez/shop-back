@extends('layouts.app')

@php
    Seo::setModel($variation->product)->setTags($variation->getSeoTags());
@endphp

@section('content')
    <div role="main" class="main">
        <div class="container">

            {{ Breadcrumbs::render('catalog.variation.show', $variation) }}

            <div class="row mb-5">
                <div class="col-md-5 mb-5 mb-md-0">
                    <div class="thumb-gallery-wrapper">
                        <div class="thumb-gallery-detail owl-carousel owl-theme manual dots-style-2 nav-style-2 nav-color-dark mb-3">
                            @foreach($variation->getImages() as $media)
                                <div>
                                    <img src="{{ $media->getUrl() }}" class="img-fluid" alt="">
                                </div>
                            @endforeach
                        </div>
                        <div class="thumb-gallery-thumbs owl-carousel owl-theme manual thumb-gallery-thumbs">
                            @foreach($variation->getImages() as $media)
                                <div>
                                <span class="d-block">
                                    <img alt="Product Image" src="{{ $media->getUrl() }}" class="img-fluid">
                                </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <h1 class="line-height-1 font-weight-bold mb-2">{{ $variation->getName() }}</h1>
                    <div class="product-info-rate d-flex mb-3">
                        @include('catalog.inc.rating-stars-list', ['rating' => $variation->product->getRating()])
                        <span style="line-height: 1">&nbsp;<a href="#productDetailReviews">{{ $variation->product->getCommentsCount() }} reviews</a></span>
                    </div>
                    <span class="price font-primary text-4"><strong class="text-color-dark" data-currency="{{$variation->currency_code}}">{{$variation->getPrice()}}</strong></span>
                    @if($variation->getPriceOld() > 0)
                        <span class="old-price font-primary text-line-trough text-2"><strong class="text-color-default" data-currency="{{$variation->currency_code}}">{{$variation->getPriceOld()}}</strong></span>
                    @endif

                    <hr class="my-4">

                    <ul class="list list-unstyled">
                        <li>AVAILABILITY:
                            <strong>
                                @if($variation->getAvailable() > 0)
                                    {{ $variation->getAvailable() }}
                                @elseif($variation->getAvailable() === 0)
                                    UNAVAILABILITY
                                @else
                                    AVAILABLE
                                @endif
                            </strong>
                        </li>
                        <li>SKU: <strong>{{ $variation->getSku() }}</strong></li>
                    </ul>

                    <hr class="my-4">
                    @foreach($variationsList['attributes'] ?? [] as $attribute)
                        <h4>{{ $attribute->name }}:</h4>
                        <div class="btn-group-toggle" {{--data-toggle="buttons"--}}>
                            @foreach($variationsList['properties']->where('attribute_id', $attribute->id) as $property)
                            {{--@foreach($variationsList['properties'][$attribute->slug] as $property)--}}
                                @if($attribute->has_image)
                                    <a class="mb-2 attribute-img-btn @if($property->is_current) active @endif"
                                       href="{{ $variationValues[$attribute->slug][$property->slug]->getUrlClient() }}"
                                       title="{{ $attribute->name . ' ' . $property->value }}"
                                    >
                                        <img src="{{ $property->getFirstMediaUrl('image', 'thumb') }}" alt="{{ $property->value }}" class="img-fluid">
                                    </a>
                                @else
                                    <a class="btn btn-primary btn-outline mb-2 @if($property->is_current) active @endif"
                                       href="{{ $variationValues[$attribute->slug][$property->slug]->getUrlClient() }}"
                                       title="{{ $attribute->name . ' ' . $property->value }}"
                                    >{{ $property->value }}</a>
                                @endif
                            @endforeach
                        </div>
                    @endforeach

                    <hr class="my-4">
                    <form class="shop-cart d-flex align-items-center" action="{{ route('cart.add', $variation) }}" method="post">
                        @csrf
                        <div class="quantity">
                            <input type="button" value="-" class="minus">
                            <input type="number" step="1" min="1" name="quantity" value="1" title="Qty" class="qty" size="2">
                            <input type="button" value="+" class="plus">
                        </div>
                        <button type="submit" class="btn btn-primary btn-rounded btn-badge font-weight-semibold btn-v-3 btn-h-2 btn-fs-2 ml-3" @if($variation->getAvailable() === 0) disabled @endif>
                            ADD TO CART {{--<span class="badge badge-danger badge-sm badge-pill text-uppercase px-2 py-1">Only $10</span>--}}
                        </button>
                        {{--
                        <button type="submit" class="add-to-cart btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-h-2 btn-fs-2 ml-3">ADD TO CART</button>
                        --}}
                        {{--<button type="submit" name="buy_now" value="1" class="btn btn btn-secondary btn-rounded font-weight-semibold btn-v-3 btn-h-2 btn-fs-2 ml-3">BUY NOW</button>--}}
                    </form>

                    <hr class="my-4">
                    <div class="d-flex align-items-center">
                        <span class="text-2">SHARE</span>
                        <ul class="social-icons social-icons-dark social-icons-1 ml-3 js-social-share">
                            <li class="social-icons-facebook"><a href="#" data-social="facebook" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                            <li class="social-icons-twitter"><a href="#" data-social="twitter" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                            <li class="social-icons-twitter"><a href="#" data-social="telegram" target="_blank" title="Telegram"><i class="fab fa-telegram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col">
                    <ul class="nav nav-tabs nav-tabs-default" id="productDetailTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold active" id="productDetailDescTab" data-toggle="tab" href="#productDetailDesc" role="tab" aria-controls="productDetailDesc" aria-expanded="true">DESCRIPTION</a>
                        </li>
                        {{--
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="productDetailMoreInfoTab" data-toggle="tab" href="#productDetailMoreInfo" role="tab" aria-controls="productDetailMoreInfo">MORE INFO</a>
                        </li>
                        --}}
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="productDetailReviewsTab" data-toggle="tab" href="#productDetailReviews" role="tab" aria-controls="productDetailReviews">COMMENTS ({{ $comments->count() }})</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="contentTabProductDetail">
                        <div class="tab-pane fade pt-4 pb-4 show active" id="productDetailDesc" role="tabpanel" aria-labelledby="productDetailDescTab">
                            {!! $variation->getBody() !!}
                        </div>
                        {{--
                        <div class="tab-pane fade pt-4 pb-4" id="productDetailMoreInfo" role="tabpanel" aria-labelledby="productDetailMoreInfoTab">
                            <table class="table">
                                <tbody>

                                @foreach($product->getAttributesPropertiesList() as $item)
                                    <tr>
                                        @if($loop->index === 0)
                                            <th class="border-top-0" scope="row">{{ mb_strtoupper($item['attribute_str']) }}</th>
                                            <td class="border-top-0">{{ $item['properties_str'] }}</td>
                                        @else
                                            <th scope="row">{{ mb_strtoupper($item['attribute_str']) }}</th>
                                            <td>{{ $item['properties_str'] }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        --}}
                        <div class="tab-pane fade pt-4 pb-4" id="productDetailReviews" role="tabpanel" aria-labelledby="productDetailReviewsTab">
                            <ul class="comments">
                                @foreach($comments as $comment)
                                    <li>
                                        <div class="comment">
                                            <div class="d-none d-sm-block">
                                                <img class="avatar rounded-circle" alt="" src="{{ \Avatar::create($comment->getAuthorName())->toBase64() }}">
                                            </div>
                                            <div class="comment-block">
                                            <span class="comment-by">
                                                <span class="comment-rating">
                                                    @include('catalog.inc.rating-stars-list', ['rating' => $comment->rating])
                                                </span>
                                                <strong class="comment-author text-color-dark">{{ $comment->getAuthorName() }}</strong>
                                                <span class="comment-date border-right-0 text-color-light-3">{{ $comment->created_at->isoFormat('MMMM Do YYYY, h:mm a') }}</span>
                                            </span>
                                                <p>{{ $comment->body }}</p>

                                                <div class="container-fluid">
                                                    <div class="lightbox" data-plugin-options="{'delegate': 'a.open-lightbox', 'type': 'image', 'gallery': {'enabled': true}, 'mainClass': 'mfp-with-zoom'}">
                                                        <div class="row mt-3">

                                                            @foreach($comment->getMedia('images') as $media)
                                                                <div class="col-12 col-md-6 col-lg-1-5 p-1">
                                                                    <div class="portfolio-item m-0 p-0">
                                                                        <div class="image-frame image-frame-style-1 image-frame-effect-1">
                                                                    <span class="image-frame-wrapper">
                                                                        <img src="{{ $media->getUrl() ?: Theme::url('img/products/product-1.jpg') }}" class="img-fluid" alt="">
                                                                        <span class="image-frame-inner-border"></span>
                                                                        <span class="image-frame-action image-frame-action-effect-1 image-frame-action-sm">
                                                                            <a href="{{ $media->getUrl() ?: Theme::url('img/products/product-1.jpg') }}" class="open-lightbox">
                                                                                <span class="image-frame-action-icon">
                                                                                    <i class="lnr lnr-magnifier text-color-light"></i>
                                                                                </span>
                                                                            </a>
                                                                        </span>
                                                                    </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            @auth
                                <div class="row mt-4 pt-2">
                                    <div class="col">
                                        <h2 class="font-weight-bold text-3 mb-3">LEAVE A COMMENT</h2>

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <form class="form-style-2" action="{{ route('products.comment', $variation->product) }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            @honeypot
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <div class="rating p-1">
                                                        <label>
                                                            <input type="radio" name="rating" value="5" title="5 stars"> 5
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="rating" value="4" title="4 stars"> 4
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="rating" value="3" title="3 stars"> 3
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="rating" value="2" title="2 stars"> 2
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="rating" value="1" title="1 star"> 1
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col">
                                                    <textarea class="form-control bg-light-5 border-0 rounded-0" placeholder="Message" rows="6" name="body" required>{{ old('body') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <input type="text" value="{{ old('added.name') }}" class="form-control border-0 rounded-0" name="added[name]" placeholder="Name" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <input type="email" value="{{ old('added.email') }}" class="form-control border-0 rounded-0" name="added[email]" placeholder="E-mail" required>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <input type="file" name="images[]" id="input-file" multiple>
                                                    {{--                                                <button type="button" class="btn btn-block btn-info js-btn-file" data-target="#input-file"><i class="fa fa-upload"></i> Add files</button>--}}
                                                </div>
                                            </div>

                                            <div class="form-row mt-2">
                                                <div class="col">
                                                    <input type="submit" value="SUBMIT" class="btn btn-primary btn-rounded btn-h-2 btn-v-2 font-weight-bold js-form-submit-prevalidate">
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

        </div>


        @if(isset($relatedVariations) && $relatedVariations->count())
            <section class="section bg-light-2 mt-5">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h2 class="font-weight-bold text-4 mb-4">Related Products</h2>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($relatedVariations as $variation)
                            <div class="col-sm-6 col-md-3 mb-4">
                                <div class="product portfolio-item portfolio-item-style-2">
                                    @include('catalog.inc.variation-frame-content', ['variation' => $variation])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif


        @include('inc.newsletter-form')
    </div>
@endsection