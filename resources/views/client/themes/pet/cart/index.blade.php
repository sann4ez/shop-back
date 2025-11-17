@extends('layouts.app')

@section('content')
    <div role="main" class="main">
        <section class="section pt-0">
            <div class="container">

                {{ Breadcrumbs::render('cart.index') }}
                {{--@include('parts.alerts-bootstrap')--}}
                {{--@include('parts.alerts-toastr')--}}

                @if(($order = \Cart::order()) && $order->purchases)
                <div class="row mb-5">
                    <div class="col">

                        <form class="shop-cart" method="post" action="{{ route('cart.sync') }}">
                            @csrf
                            <div class="table-responsive">
                                <table class="shop-cart-table w-100">
                                    <thead>
                                    <tr>
                                        <th class="product-remove"></th>
                                        <th class="product-thumbnail"></th>
                                        <th class="product-name"><strong>Product</strong></th>
                                        <th class="product-price"><strong>Price</strong></th>
                                        <th class="product-quantity"><strong>Quantity</strong></th>
                                        <th class="product-subtotal"><strong>Total</strong></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach(\Cart::purchases() as $purchase)
                                    <tr class="cart-item">
                                        <td class="product-remove">
                                            <input type="hidden" disabled name="deleted[{{ $loop->index }}]" value="{{ $purchase->id }}">
                                            <a href="#"  data-url="{{ route('cart.remove', $purchase) }}" class="js-action-form"><i class="fas fa-times" aria-label="Remove"></i></a>
                                        </td>
                                        <td class="product-thumbnail">
                                            <img src="{{ $purchase->getImageUrl() ?: Theme::url('img/products/product-1.jpg') }}" class="img-fluid" width="67" alt="" />
                                        </td>
                                        <td class="product-name">
                                            <a href="{{ $purchase->model->getUrlClient() }}">{{ $purchase->model->getName() }}</a>
                                            <p class="text-color-light-3 text-1">
                                                {{ $purchase->model->getAttributesPropertiesListStr() }}
                                            </p>
                                        </td>
                                        <td class="product-price">
                                            <span class="unit-price" data-currency="{{\Cart::order()->currency_code}}">{{$purchase->price}}</span>
                                        </td>
                                        <td class="product-quantity">
                                            <div class="quantity">
                                                <input type="hidden" name="changed[{{ $loop->index  }}][id]" value="{{ $purchase->id }}">
                                                <input type="button" value="-" class="minus">
                                                <input type="number" step="1" min="1" name="changed[{{ $loop->index }}][quantity]" value="{{ $purchase->quantity }}" title="Qty" class="qty" size="2">
                                                <input type="button" value="+" class="plus">
                                            </div>
                                        </td>
                                        <td class="product-subtotal">
                                            <span class="sub-total"><strong data-currency="{{\Cart::order()->currency_code}}">{{ $purchase->price * $purchase->quantity }}</strong></span>
                                        </td>
                                    </tr>
                                    @endforeach

                                    <tr class="border-bottom-0">
                                        <td colspan="6" class="px-0">
                                            <div class="row mx-0">
                                                <div class="col-md-5 px-0 mb-3 mb-md-0">
                                                    <div class="input-group input-group-style-3 rounded">
                                                        <input value="{{ \Cart::promocode('code') }}" type="text" name="promocode" class="form-control bg-light-5 border-0" placeholder="Enter Coupon Code..." aria-label="Enter Coupon Code">
                                                        @if(\Cart::promocode('code'))
                                                        <span class="input-group-btn bg-light-5 p-1" style="width: unset">
                                                            <button data-url="{{ route('cart.promocode.remove', ['promocode' => \Cart::promocode('code')]) }}" data-method="DELETE" data-confirm="123" class="btn font-weight-semibold btn-h-3 js-action-form" type="button" style="width: 50px; color: #c00f30">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </span>
                                                        @else
                                                        <span class="input-group-btn bg-light-5 p-1">
                                                            <button class="btn btn-primary font-weight-semibold btn-h-3 rounded h-100" type="submit">APPLY</button>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-7 text-right px-0">
                                                    <button class="btn btn-dark btn-outline btn-rounded font-weight-bold btn-h-2 btn-v-3" type="submit">UPDATE CART</button>
                                                    <a href="/cart/checkout" class="btn btn-primary btn-rounded font-weight-bold btn-h-2 btn-v-3">PROCEED TO CHECKOUT</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </form>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-12">
                        <h2 class="font-weight-bold text-4 mb-3">Cart Totals</h2>
                        <div class="table-responsive">
                            <table class="cart-totals w-100">
                                <tbody>
                                <tr>
                                    <td>
                                        <span class="cart-total-label">Cart Products</span>
                                    </td>
                                    <td>
                                        <span class="cart-total-value" data-currency="{{\Cart::order()->currency_code}}">{{ \Cart::purchasesSum() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="cart-total-label">Shipping</span>
                                    </td>
                                    <td>
                                        <span class="cart-total-value" data-currency="{{\Cart::order()->currency_code}}">{{ \Cart::deliverySum() > 0 ? \Cart::deliverySum() : 'Free Delivery' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="cart-total-label">Discount</span>
                                    </td>
                                    <td>
                                        <span class="cart-total-value" data-currency="{{\Cart::order()->currency_code}}">{{ \Cart::totalDiscountSum() + \Cart::deliveryDiscountSum() }}</span>
                                    </td>
                                </tr>
                                <tr class="border-bottom-0">
                                    <td>
                                        <span class="cart-total-label">Total</span>
                                    </td>
                                    <td>
                                        <span class="cart-total-value text-color-primary text-4" data-currency="{{\Cart::order()->currency_code}}">{{ \Cart::totalSum() }}</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @else
                    @include('cart.inc.empty')
                @endif
            </div>
        </section>
        @include('inc.newsletter-form')
    </div>
@endsection